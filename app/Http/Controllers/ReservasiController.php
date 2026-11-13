<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
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

        return view('reservasi.index', compact('reservasi'));
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

    public function confirm(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        DB::transaction(function () use ($reservasi): void {
            $lockedReservation = Reservasi::whereKey($reservasi->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedReservation->pool_id !== session('admin_pool_id')) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }

            if ($lockedReservation->status_reservasi !== 'Menunggu') {
                throw ValidationException::withMessages([
                    'reservasi' => 'Reservasi ini tidak lagi berstatus menunggu.',
                ]);
            }

            if ($lockedReservation->status_pembayaran !== 'paid') {
                throw ValidationException::withMessages([
                    'reservasi' => 'Pembayaran belum lunas. Periksa struk pengunjung terlebih dahulu.',
                ]);
            }

            $lockedReservation->forceFill([
                'status_reservasi' => 'Dikonfirmasi',
            ])->save();
        });

        return redirect()
            ->route('reservasi.dikonfirmasi')
            ->with('success', 'Reservasi berhasil dikonfirmasi setelah struk diperiksa.');
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
            'status_reservasi.in' => 'Status reservasi tidak valid. Gunakan tombol konfirmasi setelah memeriksa struk pembayaran.',
        ]);

        if (
            $validated['status_reservasi'] === 'Dikonfirmasi'
            && $reservasi->status_reservasi !== 'Dikonfirmasi'
        ) {
            throw ValidationException::withMessages([
                'status_reservasi' => 'Gunakan tombol Konfirmasi setelah memeriksa struk pembayaran di loket.',
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
}
