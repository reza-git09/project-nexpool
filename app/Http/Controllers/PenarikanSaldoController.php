<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Models\Pool;
use App\Models\Reservasi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PenarikanSaldoController extends Controller
{
    private const BANK_OPTIONS = [
        'BCA' => 'Bank Central Asia',
        'BRI' => 'Bank Rakyat Indonesia',
        'BNI' => 'Bank Negara Indonesia',
        'Mandiri' => 'Bank Mandiri',
        'BTN' => 'Bank Tabungan Negara',
        'BSI' => 'Bank Syariah Indonesia',
        'CIMB Niaga' => 'Bank CIMB Niaga',
        'Danamon' => 'Bank Danamon Indonesia',
        'Permata' => 'Bank Permata',
        'BTPN' => 'Bank BTPN',
    ];

    public function index(): View
    {
        $poolId = $this->poolId();
        $availableBalanceInCents = $this->availableBalanceInCentsForPool($poolId);
        $withdrawals = PenarikanSaldo::where('pool_id', $poolId)
            ->latest()
            ->paginate(15);

        return view('penarikan_saldo.index', [
            'withdrawals' => $withdrawals,
            'availableBalance' => $availableBalanceInCents / 100,
            'bankOptions' => self::BANK_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $poolId = $this->poolId();

        $validated = $request->validate([
            'bank_name' => ['required', 'string', Rule::in(array_keys(self::BANK_OPTIONS))],
            'account_number' => ['required', 'string', 'regex:/^[0-9]{5,30}$/'],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:50000000',
            ],
        ], [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_number.regex' => 'Nomor rekening harus berisi 5 sampai 30 angka.',
            'amount.required' => 'Jumlah pencairan wajib diisi.',
            'amount.gt' => 'Jumlah pencairan harus lebih dari nol.',
            'amount.decimal' => 'Jumlah pencairan maksimal memiliki 2 angka desimal.',
            'amount.max' => 'Maksimal jumlah pencairan per pengajuan adalah Rp 50.000.000.',
        ]);

        DB::transaction(function () use ($poolId, $validated): void {
            Pool::query()
                ->where('pool_id', $poolId)
                ->lockForUpdate()
                ->firstOrFail();

            $availableBalanceInCents = $this->availableBalanceInCentsForPool($poolId);
            if ($this->amountInCents((string) $validated['amount']) > $availableBalanceInCents) {
                throw ValidationException::withMessages([
                    'amount' => sprintf(
                        'Jumlah pencairan melebihi pendapatan valid kolam saat ini. Maksimal yang bisa dicairkan adalah Rp %s.',
                        number_format($availableBalanceInCents / 100, 2, ',', '.'),
                    ),
                ]);
            }

            PenarikanSaldo::create([
                'pool_id' => $poolId,
                'requested_by' => (int) session('admin_id'),
                'bank_name' => trim($validated['bank_name']),
                'account_number' => $validated['account_number'],
                'amount' => $validated['amount'],
                'status' => PenarikanSaldo::STATUS_PROSES,
            ]);
        });

        return redirect()
            ->route('penarikan-saldo.index')
            ->with('success', 'Pengajuan pencairan saldo berhasil dikirim.');
    }

    public function indexForAdminMaster(): View
    {
        $withdrawals = PenarikanSaldo::with(['pool', 'requester'])
            ->latest()
            ->paginate(20);

        return view('admin_master.penarikan_saldo.index', compact('withdrawals'));
    }

    public function uploadTransferProof(Request $request, PenarikanSaldo $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'transfer_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'extensions:jpg,jpeg,png', 'max:5120'],
        ], [
            'transfer_proof.required' => 'Pilih bukti transfer terlebih dahulu.',
            'transfer_proof.image' => 'Bukti transfer harus berupa gambar.',
            'transfer_proof.mimes' => 'Bukti transfer harus berupa JPG, JPEG, atau PNG.',
            'transfer_proof.extensions' => 'Bukti transfer harus memiliki ekstensi JPG, JPEG, atau PNG.',
            'transfer_proof.max' => 'Ukuran bukti transfer maksimal 5 MB.',
        ]);

        $newPath = $validated['transfer_proof']->store('withdrawal-proofs', 'local');
        if ($newPath === false || ! Storage::disk('local')->exists($newPath)) {
            if ($newPath !== false) {
                Storage::disk('local')->delete($newPath);
            }

            throw new RuntimeException('Bukti transfer gagal disimpan.');
        }

        try {
            $result = DB::transaction(function () use ($withdrawal, $newPath): array {
                Pool::query()
                    ->where('pool_id', $withdrawal->pool_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedWithdrawal = PenarikanSaldo::whereKey($withdrawal->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedWithdrawal->status !== PenarikanSaldo::STATUS_PROSES) {
                    return ['confirmed' => false, 'old_path' => null];
                }

                if ($this->amountInCents($lockedWithdrawal->amount)
                    > $this->availableBalanceInCentsForPool($lockedWithdrawal->pool_id)) {
                    throw ValidationException::withMessages([
                        'transfer_proof' => 'Saldo tersedia tidak mencukupi untuk memproses pengajuan ini.',
                    ]);
                }

                $oldPath = $lockedWithdrawal->transfer_proof_path;
                $lockedWithdrawal->forceFill([
                    'transfer_proof_path' => $newPath,
                    'transfer_proof_uploaded_by' => session('admin_id'),
                    'transfer_proof_uploaded_at' => now(),
                    'status' => PenarikanSaldo::STATUS_TERKONFIRMASI,
                    'processed_by' => session('admin_id'),
                    'processed_at' => now(),
                    'rejection_reason' => null,
                ])->save();

                return ['confirmed' => true, 'old_path' => $oldPath];
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($newPath);
            throw $exception;
        }

        if (! $result['confirmed']) {
            Storage::disk('local')->delete($newPath);

            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($result['old_path'] !== null) {
            Storage::disk('local')->delete($result['old_path']);
        }

        return back()->with('success', 'Bukti transfer berhasil dikirim dan pengajuan telah terkonfirmasi.');
    }

    public function showTransferProof(PenarikanSaldo $withdrawal): StreamedResponse
    {
        $role = (string) session('admin_role', '');
        $sessionPoolId = (string) session('admin_pool_id', '');

        $isAdminMaster = $role === 'admin_master';
        // Admin kolam: boleh melihat bukti hanya untuk kolam miliknya sendiri.
        // Dicocokkan lewat pool_id (dibandingkan sebagai string), bukan lewat
        // label role, supaya tidak gagal kalau nama role di session berbeda.
        $isOwningPoolAdmin = ! $isAdminMaster
            && $sessionPoolId !== ''
            && $sessionPoolId === (string) $withdrawal->pool_id;

        if (! $isAdminMaster && ! $isOwningPoolAdmin) {
            Log::warning('Akses bukti transfer ditolak', [
                'withdrawal_id' => $withdrawal->getKey(),
                'role' => $role,
                'session_pool' => $sessionPoolId,
                'withdrawal_pool' => $withdrawal->pool_id,
            ]);
        }

        abort_unless($isAdminMaster || $isOwningPoolAdmin, 403, 'Anda tidak memiliki akses ke bukti transfer ini.');
        abort_if($withdrawal->transfer_proof_path === null, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($withdrawal->transfer_proof_path), 404);

        return $disk->response(
            $withdrawal->transfer_proof_path,
            basename($withdrawal->transfer_proof_path),
            [
                'Content-Type' => $disk->mimeType($withdrawal->transfer_proof_path) ?: 'application/octet-stream',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ],
            'inline',
        );
    }

    private function poolId(): string
    {
        $poolId = (string) session('admin_pool_id', '');
        abort_if($poolId === '', 403, 'Akun ini tidak terhubung ke wisata kolam renang.');

        return $poolId;
    }

    private function availableBalanceInCentsForPool(string $poolId): int
    {
        $totalPendapatan = Reservasi::query()
            ->where('pool_id', $poolId)
            ->where('status_pembayaran', 'paid')
            ->sum('total_harga');
        $totalPenarikan = $this->withdrawalAmountForPool($poolId);
        $totalPendapatanInCents = $this->amountInCents($totalPendapatan);
        $totalPenarikanInCents = $this->amountInCents($totalPenarikan);

        return max($totalPendapatanInCents - $totalPenarikanInCents, 0);
    }

    private function withdrawalAmountForPool(string $poolId): string
    {
        return (string) PenarikanSaldo::query()
            ->where('pool_id', $poolId)
            ->where('status', PenarikanSaldo::STATUS_TERKONFIRMASI)
            ->sum('amount');
    }

    private function amountInCents(string|int|float $amount): int
    {
        $amount = (string) $amount;
        [$wholeAmount, $fractionalAmount] = array_pad(explode('.', $amount, 2), 2, '0');
        $fractionalAmount = str_pad(substr($fractionalAmount, 0, 2), 2, '0');

        return ((int) $wholeAmount * 100) + (int) $fractionalAmount;
    }
}
