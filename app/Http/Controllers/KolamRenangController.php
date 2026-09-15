<?php

namespace App\Http\Controllers;

use App\Models\KolamRenang;
use Illuminate\Http\Request;

class KolamRenangController extends Controller
{
    /**
     * Menampilkan daftar semua kolam renang.
     */
    public function index()
    {
        $kolamRenang = KolamRenang::orderBy('pool_id')->get();

        return view('kolam_renang.index', compact('kolamRenang'));
    }

    /**
     * Menampilkan form tambah kolam renang.
     */
    public function create()
    {
        return view('kolam_renang.create');
    }

    /**
     * Menyimpan data kolam renang baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pool_id' => 'required|string|max:255|unique:kolam_renang,pool_id',
            'nama_kolam' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'maps_url' => 'nullable|string|max:255',
            'gambar' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        KolamRenang::create([
            'pool_id' => $request->pool_id,
            'nama_kolam' => $request->nama_kolam,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'deskripsi' => $request->deskripsi,
            'maps_url' => $request->maps_url,
            'gambar' => $request->gambar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('kolam-renang.index')
            ->with('success', 'Kolam renang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kolam renang.
     */
    public function show(KolamRenang $kolamRenang)
    {
        return view('kolam_renang.show', compact('kolamRenang'));
    }

    /**
     * Menampilkan form edit kolam renang.
     */
    public function edit(KolamRenang $kolamRenang)
    {
        return view('kolam_renang.edit', compact('kolamRenang'));
    }

    /**
     * Memperbarui data kolam renang.
     */
    public function update(Request $request, KolamRenang $kolamRenang)
    {
        $request->validate([
            'pool_id' => 'required|string|max:255|unique:kolam_renang,pool_id,' . $kolamRenang->id,
            'nama_kolam' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'maps_url' => 'nullable|string|max:255',
            'gambar' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $kolamRenang->update([
            'pool_id' => $request->pool_id,
            'nama_kolam' => $request->nama_kolam,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'deskripsi' => $request->deskripsi,
            'maps_url' => $request->maps_url,
            'gambar' => $request->gambar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('kolam-renang.index')
            ->with('success', 'Data kolam renang berhasil diperbarui.');
    }

    /**
     * Menghapus kolam renang.
     */
    public function destroy(KolamRenang $kolamRenang)
    {
        $kolamRenang->delete();

        return redirect()
            ->route('kolam-renang.index')
            ->with('success', 'Kolam renang berhasil dihapus.');
    }
}