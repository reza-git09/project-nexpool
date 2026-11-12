<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Models\Reservasi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenarikanSaldoController extends Controller
{
    public function index(): View
    {
        $poolId = $this->poolId();
        $availableBalance = $this->availableBalanceForPool($poolId);
        $withdrawals = PenarikanSaldo::where('pool_id', $poolId)
            ->latest()
            ->paginate(15);

        return view('penarikan_saldo.index', compact('withdrawals', 'availableBalance'));
    }

    public function store(Request $request): RedirectResponse
    {
        $poolId = $this->poolId();
        $availableBalance = $this->availableBalanceForPool($poolId);

        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'regex:/^[0-9]{5,30}$/'],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:9999999999999.99',
                function (string $attribute, mixed $value, \Closure $fail) use ($availableBalance): void {
                    $amount = (float) $value;

                    if ($amount > $availableBalance) {
                        $fail(sprintf(
                            'Jumlah pencairan melebihi pendapatan valid kolam saat ini. Maksimal yang bisa dicairkan adalah Rp %s.',
                            number_format($availableBalance, 2, ',', '.'),
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
        $withdrawals = PenarikanSaldo::with('pool')
            ->latest()
            ->paginate(20);

        return view('admin_master.penarikan_saldo.index', compact('withdrawals'));
    }

    public function complete(PenarikanSaldo $withdrawal): RedirectResponse
    {
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
        $totalPendapatan = (float) Reservasi::query()
            ->where('pool_id', $poolId)
            ->where('status_pembayaran', 'paid')
            ->sum('total_harga');

        $totalPenarikan = $this->withdrawalAmountForPool($poolId);

        return max($totalPendapatan - $totalPenarikan, 0.0);
    }

    private function withdrawalAmountForPool(string $poolId): float
    {
        return (float) PenarikanSaldo::query()
            ->where('pool_id', $poolId)
            ->whereIn('status', [
                PenarikanSaldo::STATUS_PROSES,
                PenarikanSaldo::STATUS_BERHASIL,
            ])
            ->sum('amount');
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
