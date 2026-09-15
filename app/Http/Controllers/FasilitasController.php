<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    // Menampilkan fasilitas sesuai pool admin yang login
    public function index()
    {
        $fasilitas = Fasilitas::where('pool_id', session('admin_pool_id'))
            ->orderBy('nama_fasilitas')
            ->get();

        return view('fasilitas.index', compact('fasilitas'));
    }

    // Menampilkan form tambah fasilitas
    public function create()
    {
        return view('fasilitas.create');
    }

    // Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Fasilitas::create([
            'pool_id' => session('admin_pool_id'),
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    // Menampilkan detail fasilitas
    public function show(Fasilitas $fasilita)
    {
        // Cegah akses ke fasilitas milik pool lain
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.show', compact('fasilita'));
    }

    // Menampilkan form edit
    public function edit(Fasilitas $fasilita)
    {
        // Cegah edit fasilitas milik pool lain
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.edit', compact('fasilita'));
    }

    // Memperbarui fasilitas
    public function update(Request $request, Fasilitas $fasilita)
    {
        // Cegah update fasilitas milik pool lain
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $fasilita->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    // Menghapus fasilitas
    public function destroy(Fasilitas $fasilita)
    {
        // Cegah hapus fasilitas milik pool lain
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $fasilita->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}