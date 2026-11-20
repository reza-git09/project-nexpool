<?php

namespace App\Http\Controllers;

use App\Models\RescheduleRequest;
use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservasiController extends Controller
{
    /**
     * RESERVASI MENUNGGU
     */
    public function index()
    {
        $poolId = session('admin_pool_id');

        $reservasi = Reservasi::where('pool_id', $poolId)
            ->where('status_reservasi', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        $rescheduleRequests = RescheduleRequest::query()
            ->with('reservasi')
            ->where('status', RescheduleRequest::STATUS_AWAITING_APPROVAL)
            ->where('expires_at', '>', now())
            ->whereHas(
                'reservasi',
                fn (Builder $query): Builder => $query->where('pool_id', $poolId),
            )
            ->latest('id')
            ->get();

        return view('reservasi.index', compact('reservasi', 'rescheduleRequests'));
    }

    /**
     * RESERVASI DIKONFIRMASI
     */
    public function dikonfirmasi()
    {
        $poolId = session('admin_pool_id');

        $reservasi = Reservasi::where('pool_id', $poolId)
            ->where('status_reservasi', 'Dikonfirmasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservasi.dikonfirmasi', compact('reservasi'));
    }

    /**
     * DETAIL RESERVASI
     */
    public function show(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.show', compact('reservasi'));
    }

    /**
     * EDIT / KELOLA RESERVASI
     */
    public function edit(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.edit', compact('reservasi'));
    }

    /**
     * UPDATE STATUS RESERVASI
     */
    public function update(Request $request, Reservasi $reservasi)
    {
        // Cek akses berdasarkan pool admin
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Validasi status
        $validated = $request->validate([
            'status_reservasi' => [
                'required',
                'in:Menunggu,Dikonfirmasi,Selesai,Dibatalkan',
            ],
        ], [
            'status_reservasi.required' => 'Status reservasi wajib dipilih.',
            'status_reservasi.in' => 'Status reservasi tidak valid.',
        ]);

        if (
            $validated['status_reservasi'] === 'Dikonfirmasi'
            && $reservasi->status_reservasi !== 'Dikonfirmasi'
            && $reservasi->status_pembayaran !== 'paid'
        ) {
            throw ValidationException::withMessages([
                'status_reservasi' => 'Reservasi hanya dapat dikonfirmasi setelah pembayaran lunas dan struk pengunjung diperiksa di loket.',
            ]);
        }

        // Simpan status baru
        $reservasi->status_reservasi = $validated['status_reservasi'];
        $reservasi->save();

        // Jika dikonfirmasi
        if ($reservasi->status_reservasi === 'Dikonfirmasi') {
            return redirect()
                ->route('reservasi.dikonfirmasi')
                ->with('success', 'Reservasi berhasil dikonfirmasi.');
        }

        // Status selain Dikonfirmasi
        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function approveReschedule(RescheduleRequest $rescheduleRequest): RedirectResponse
    {
        $approved = DB::transaction(function () use ($rescheduleRequest): bool {
            $lockedRequest = RescheduleRequest::query()
                ->whereKey($rescheduleRequest->id)
                ->lockForUpdate()
                ->firstOrFail();
            $reservasi = Reservasi::query()
                ->whereKey($lockedRequest->reservasi_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservasi->pool_id !== session('admin_pool_id')) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }

            if ($lockedRequest->status !== RescheduleRequest::STATUS_AWAITING_APPROVAL) {
                throw ValidationException::withMessages([
                    'reschedule' => 'Pengajuan ini sudah diproses atau tidak lagi aktif.',
                ]);
            }

            if ($lockedRequest->expires_at?->isPast()) {
                $lockedRequest->status = RescheduleRequest::STATUS_EXPIRED;
                $lockedRequest->save();

                return false;
            }

            if (
                $reservasi->status_pembayaran !== 'paid'
                || in_array($reservasi->status_reservasi, ['Dibatalkan', 'Selesai'], true)
            ) {
                $lockedRequest->status = RescheduleRequest::STATUS_EXPIRED;
                $lockedRequest->save();

                return false;
            }

            if ($lockedRequest->selisih_harga > 0) {
                $lockedRequest->status = RescheduleRequest::STATUS_AWAITING_PAYMENT;
                $lockedRequest->approved_at = now();
                $lockedRequest->save();

                return true;
            }

            $reservasi->tanggal_kunjungan = $lockedRequest->tanggal_baru;
            $reservasi->save();

            $lockedRequest->status = RescheduleRequest::STATUS_COMPLETED;
            $lockedRequest->payment_status = 'not_required';
            $lockedRequest->approved_at = now();
            $lockedRequest->save();

            return true;
        });

        if (! $approved) {
            return back()->with('error', 'Pengajuan reschedule sudah kedaluwarsa atau tiket tidak lagi memenuhi syarat.');
        }

        return back()->with(
            'success',
            $rescheduleRequest->selisih_harga > 0
                ? 'Pengajuan disetujui. Pengunjung dapat membayar selisih harga.'
                : 'Pengajuan disetujui dan tanggal kunjungan berhasil diubah.',
        );
    }

    public function rejectReschedule(
        Request $request,
        RescheduleRequest $rescheduleRequest,
    ): RedirectResponse {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:500'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $rejected = DB::transaction(function () use ($rescheduleRequest, $validated): bool {
            $lockedRequest = RescheduleRequest::query()
                ->whereKey($rescheduleRequest->id)
                ->lockForUpdate()
                ->firstOrFail();
            $reservasi = Reservasi::query()
                ->whereKey($lockedRequest->reservasi_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservasi->pool_id !== session('admin_pool_id')) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }

            if ($lockedRequest->status !== RescheduleRequest::STATUS_AWAITING_APPROVAL) {
                throw ValidationException::withMessages([
                    'reschedule' => 'Pengajuan ini sudah diproses atau tidak lagi aktif.',
                ]);
            }

            if ($lockedRequest->expires_at?->isPast()) {
                $lockedRequest->status = RescheduleRequest::STATUS_EXPIRED;
                $lockedRequest->save();

                return false;
            }

            $lockedRequest->status = RescheduleRequest::STATUS_REJECTED;
            $lockedRequest->alasan_penolakan = $validated['alasan_penolakan'];
            $lockedRequest->save();

            return true;
        });

        if (! $rejected) {
            return back()->with('error', 'Pengajuan reschedule sudah kedaluwarsa.');
        }

        return back()->with('success', 'Pengajuan reschedule berhasil ditolak.');
    }
}
