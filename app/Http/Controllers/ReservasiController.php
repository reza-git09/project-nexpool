<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    // Menampilkan reservasi hanya milik pool admin yang login
    public function index()
    {
        $reservasi = Reservasi::where('pool_id', session('admin_pool_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservasi.index', compact('reservasi'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('reservasi.create');
    }

    // Menyimpan reservasi baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_reservasi' => 'required|string|max:255|unique:reservasis,kode_reservasi',
            'nama_pengunjung' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'tanggal_kunjungan' => 'required|date',
            'jumlah_dewasa' => 'required|integer|min:0',
            'jumlah_anak' => 'required|integer|min:0',
            'total_harga' => 'required|numeric|min:0',
            'status_reservasi' => 'required|in:Menunggu,Dikonfirmasi,Selesai,Dibatalkan',
        ]);

        Reservasi::create([
            'kode_reservasi' => $request->kode_reservasi,
            'pool_id' => session('admin_pool_id'),
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jumlah_dewasa' => $request->jumlah_dewasa,
            'jumlah_anak' => $request->jumlah_anak,
            'total_harga' => $request->total_harga,
            'status_reservasi' => $request->status_reservasi,
        ]);

        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Reservasi berhasil ditambahkan.');
    }

    // Menampilkan detail reservasi
    public function show(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.show', compact('reservasi'));
    }

    // Menampilkan form edit
    public function edit(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('reservasi.edit', compact('reservasi'));
    }

    // Memperbarui reservasi
    public function update(Request $request, Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'kode_reservasi' => 'required|string|max:255|unique:reservasis,kode_reservasi,' . $reservasi->id,
            'nama_pengunjung' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'tanggal_kunjungan' => 'required|date',
            'jumlah_dewasa' => 'required|integer|min:0',
            'jumlah_anak' => 'required|integer|min:0',
            'total_harga' => 'required|numeric|min:0',
            'status_reservasi' => 'required|in:Menunggu,Dikonfirmasi,Selesai,Dibatalkan',
        ]);

        $reservasi->update([
            'kode_reservasi' => $request->kode_reservasi,
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jumlah_dewasa' => $request->jumlah_dewasa,
            'jumlah_anak' => $request->jumlah_anak,
            'total_harga' => $request->total_harga,
            'status_reservasi' => $request->status_reservasi,
        ]);

        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    // Menghapus reservasi
    public function destroy(Reservasi $reservasi)
    {
        if ($reservasi->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $reservasi->delete();

        return redirect()
            ->route('reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }
}