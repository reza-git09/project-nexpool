<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    /**
     * Menampilkan reservasi hanya milik pool admin yang sedang login.
     */
    public function index()
    {
        $reservasi = Reservasi::where('pool_id', session('admin_pool_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservasi.index', compact('reservasi'));
    }

    /**
     * Menampilkan detail reservasi.
     */
    public function show(Reservasi $reservasi)
    {
        // Pastikan reservasi hanya bisa dilihat oleh admin
        // dari pool yang sesuai.
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.show', compact('reservasi'));
    }

    /**
     * Menampilkan form untuk mengubah status reservasi.
     */
    public function edit(Reservasi $reservasi)
    {
        // Pastikan reservasi milik pool admin yang login.
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.edit', compact('reservasi'));
    }

    /**
     * Memperbarui status reservasi.
     *
     * Data reservasi seperti nama pengunjung,
     * nomor HP, jumlah tiket, dan total harga
     * tidak diubah oleh admin.
     */
    public function update(Request $request, Reservasi $reservasi)
    {
        // Pastikan reservasi milik pool admin yang login.
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'status_reservasi' => 'required|in:Menunggu,Dikonfirmasi,Selesai,Dibatalkan',
        ]);

        $reservasi->update([
            'status_reservasi' => $request->status_reservasi,
        ]);

        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }
}