<?php

namespace App\Http\Controllers;

use App\Models\HargaTiket;
use Illuminate\Http\Request;

class HargaTiketController extends Controller
{
    // Menampilkan harga tiket sesuai pool admin yang login
    public function index()
    {
        $hargaTiket = HargaTiket::where('pool_id', session('admin_pool_id'))
            ->orderBy('kategori')
            ->orderBy('jenis_hari')
            ->get();

        return view('harga_tiket.index', compact('hargaTiket'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('harga_tiket.create');
    }

    // Menyimpan harga tiket baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:Dewasa,Anak',
            'jenis_hari' => 'required|in:Weekday,Weekend',
            'harga' => 'required|numeric|min:0',
        ]);

        HargaTiket::create([
            'pool_id' => session('admin_pool_id'),
            'kategori' => $request->kategori,
            'jenis_hari' => $request->jenis_hari,
            'harga' => $request->harga,
        ]);

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(HargaTiket $hargaTiket)
    {
        // Mencegah admin mengakses data pool lain
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('harga_tiket.edit', compact('hargaTiket'));
    }

    // Memperbarui harga tiket
    public function update(Request $request, HargaTiket $hargaTiket)
    {
        // Mencegah admin mengubah data pool lain
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'kategori' => 'required|in:Dewasa,Anak',
            'jenis_hari' => 'required|in:Weekday,Weekend',
            'harga' => 'required|numeric|min:0',
        ]);

        $hargaTiket->update([
            'kategori' => $request->kategori,
            'jenis_hari' => $request->jenis_hari,
            'harga' => $request->harga,
        ]);

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil diperbarui.');
    }

    // Menghapus harga tiket
    public function destroy(HargaTiket $hargaTiket)
    {
        // Mencegah admin menghapus data pool lain
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $hargaTiket->delete();

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil dihapus.');
    }
}