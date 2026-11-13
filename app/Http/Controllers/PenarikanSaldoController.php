<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Models\Pool;
use App\Models\Reservasi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;
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
        $availableBalance = $this->availableBalanceForPool($poolId);
        $withdrawals = PenarikanSaldo::where('pool_id', $poolId)
            ->latest()
            ->paginate(15);

        return view('penarikan_saldo.index', [
            'withdrawals' => $withdrawals,
            'availableBalance' => $availableBalance,
            'bankOptions' => self::BANK_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $poolId = $this->poolId();
        $availableBalanceInCents = $this->availableBalanceInCentsForPool($poolId);
        $availableBalance = $availableBalanceInCents / 100;

        $validated = $request->validate([
            'bank_name' => ['required', 'string', Rule::in(array_keys(self::BANK_OPTIONS))],
            'account_number' => ['required', 'string', 'regex:/^[0-9]{5,30}$/'],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:9999999999999.99',
                function (string $attribute, mixed $value, \Closure $fail) use ($availableBalanceInCents): void {
                    if ($this->amountInCents((string) $value) > $availableBalanceInCents) {
                        $fail(sprintf(
                            'Jumlah pencairan melebihi pendapatan valid kolam saat ini. Maksimal yang bisa dicairkan adalah Rp %s.',
                            number_format($availableBalanceInCents / 100, 2, ',', '.'),
                        ));
                    }
                },
            ],
        ], [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_number.regex' => 'Nomor rekening harus berisi 5 sampai 30 angka.',
            'amount.required' => 'Jumlah pencairan wajib diisi.',
            'amount.gt' => 'Jumlah pencairan harus lebih dari nol.',
            'amount.decimal' => 'Jumlah pencairan maksimal memiliki 2 angka desimal.',
            'amount.max' => 'Jumlah pencairan melebihi batas maksimum sistem.',
        ]);

        PenarikanSaldo::create([
            'pool_id' => $poolId,
            'requested_by' => (int) session('admin_id'),
            'bank_name' => trim($validated['bank_name']),
            'account_number' => $validated['account_number'],
            'amount' => $validated['amount'],
            'status' => PenarikanSaldo::STATUS_PROSES,
        ]);

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
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'transfer_proof.required' => 'Pilih bukti transfer terlebih dahulu.',
            'transfer_proof.file' => 'Bukti transfer harus berupa file.',
            'transfer_proof.mimes' => 'Bukti transfer harus berupa JPG, PNG, atau PDF.',
            'transfer_proof.max' => 'Ukuran bukti transfer maksimal 5 MB.',
        ]);

        $newPath = $validated['transfer_proof']->store('withdrawal-proofs', 'local');
        if ($newPath === false) {
            throw new RuntimeException('Bukti transfer gagal disimpan.');
        }

        try {
            $oldPath = DB::transaction(function () use ($withdrawal, $newPath): ?string {
                $lockedWithdrawal = PenarikanSaldo::whereKey($withdrawal->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedWithdrawal->status !== PenarikanSaldo::STATUS_PROSES) {
                    throw ValidationException::withMessages([
                        'transfer_proof' => 'Bukti hanya dapat dikirim untuk pengajuan yang masih diproses.',
                    ]);
                }

                $oldPath = $lockedWithdrawal->transfer_proof_path;
                $lockedWithdrawal->forceFill([
                    'transfer_proof_path' => $newPath,
                    'transfer_proof_uploaded_by' => session('admin_id'),
                    'transfer_proof_uploaded_at' => now(),
                ])->save();

                return $oldPath;
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($newPath);
            throw $exception;
        }

        if ($oldPath !== null) {
            Storage::disk('local')->delete($oldPath);
        }

        return back()->with('success', 'Bukti transfer berhasil dikirim. Pengajuan sekarang dapat dikonfirmasi.');
    }

    public function showTransferProof(PenarikanSaldo $withdrawal)
    {
        abort_if($withdrawal->transfer_proof_path === null, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($withdrawal->transfer_proof_path), 404);

        return $disk->response(
            $withdrawal->transfer_proof_path,
            basename($withdrawal->transfer_proof_path),
        );
    }

    public function complete(PenarikanSaldo $withdrawal): RedirectResponse
    {
        if ($withdrawal->transfer_proof_path === null) {
            throw ValidationException::withMessages([
                'withdrawal' => 'Kirim bukti transfer terlebih dahulu sebelum mengonfirmasi pencairan.',
            ]);
        }

        $updated = $this->updatePendingWithdrawal(
            $withdrawal,
            PenarikanSaldo::STATUS_BERHASIL,
        );

        return back()->with(
            $updated ? 'success' : 'error',
            $updated
                ? 'Pengajuan ditandai berhasil dicairkan.'
                : 'Pengajuan ini sudah diproses sebelumnya.',
        );
    }

    public function reject(Request $request, PenarikanSaldo $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $updated = $this->updatePendingWithdrawal(
            $withdrawal,
            PenarikanSaldo::STATUS_DITOLAK,
            $validated['rejection_reason'] ?? null,
        );

        return back()->with(
            $updated ? 'success' : 'error',
            $updated
                ? 'Pengajuan pencairan saldo ditolak.'
                : 'Pengajuan ini sudah diproses sebelumnya.',
        );
    }

    private function poolId(): string
    {
        $poolId = (string) session('admin_pool_id', '');
        abort_if($poolId === '', 403, 'Akun ini tidak terhubung ke wisata kolam renang.');

        return $poolId;
    }

    private function availableBalanceForPool(string $poolId): float
    {
        return $this->availableBalanceInCentsForPool($poolId) / 100;
    }

    private function availableBalanceInCentsForPool(string $poolId): int
    {
        $totalPendapatan = Reservasi::query()
            ->where('pool_id', $poolId)
            ->where('status_pembayaran', 'paid')
            ->sum('total_harga');

        $totalPenarikan = $this->withdrawalAmountForPool($poolId);

        return max(
            $this->amountInCents($totalPendapatan) - $this->amountInCents($totalPenarikan),
            0,
        );
    }

    private function withdrawalAmountForPool(string $poolId): string
    {
        return (string) PenarikanSaldo::query()
            ->where('pool_id', $poolId)
            ->where('status', PenarikanSaldo::STATUS_BERHASIL)
            ->sum('amount');
    }

    private function amountInCents(string|int|float $amount): int
    {
        $amount = (string) $amount;
        [$wholeAmount, $fractionalAmount] = array_pad(explode('.', $amount, 2), 2, '0');
        $fractionalAmount = str_pad(substr($fractionalAmount, 0, 2), 2, '0');

        return ((int) $wholeAmount * 100) + (int) $fractionalAmount;
    }

    private function updatePendingWithdrawal(
        PenarikanSaldo $withdrawal,
        string $status,
        ?string $rejectionReason = null,
    ): bool {
        return DB::transaction(function () use ($withdrawal, $status, $rejectionReason): bool {
            $lockedWithdrawal = PenarikanSaldo::whereKey($withdrawal->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedWithdrawal->status !== PenarikanSaldo::STATUS_PROSES) {
                return false;
            }

            if ($status === PenarikanSaldo::STATUS_BERHASIL) {
                if ($lockedWithdrawal->transfer_proof_path === null) {
                    throw ValidationException::withMessages([
                        'withdrawal' => 'Kirim bukti transfer terlebih dahulu sebelum mengonfirmasi pencairan.',
                    ]);
                }

                Pool::query()
                    ->where('pool_id', $lockedWithdrawal->pool_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($this->amountInCents($lockedWithdrawal->amount)
                    > $this->availableBalanceInCentsForPool($lockedWithdrawal->pool_id)) {
                    throw ValidationException::withMessages([
                        'withdrawal' => 'Saldo tersedia tidak mencukupi untuk mencairkan pengajuan ini.',
                    ]);
                }
            }

            $lockedWithdrawal->forceFill([
                'status' => $status,
                'processed_by' => session('admin_id'),
                'processed_at' => now(),
                'rejection_reason' => $rejectionReason,
            ])->save();

            return true;
        });
    }
}
