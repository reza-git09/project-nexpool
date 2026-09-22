<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::where('pool_id', session('admin_pool_id'))
            ->orderBy('nama_fasilitas')
            ->get();

        return view('fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('fasilitas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255|unique:fasilitas,nama_fasilitas',
            'deskripsi' => 'required|string|unique:fasilitas,deskripsi',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.regex' => 'Nama fasilitas hanya boleh berisi huruf dan spasi (tidak boleh menggunakan angka atau simbol).',
            'nama_fasilitas.unique' => 'Nama fasilitas ini sudah ada, tidak boleh sama.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.unique' => 'Deskripsi ini sudah digunakan oleh fasilitas lain, tidak boleh sama.',
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('fasilitas', 'public');
        }

        Fasilitas::create([
            'pool_id' => session('admin_pool_id'),
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Fasilitas $fasilita)
    {
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.show', compact('fasilita'));
    }

    public function edit(Fasilitas $fasilita)
    {
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.edit', compact('fasilita'));
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'nama_fasilitas' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255|unique:fasilitas,nama_fasilitas,' . $fasilita->id,
            'deskripsi' => 'required|string|unique:fasilitas,deskripsi,' . $fasilita->id,
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.regex' => 'Nama fasilitas hanya boleh berisi huruf dan spasi (tidak boleh menggunakan angka atau simbol).',
            'nama_fasilitas.unique' => 'Nama fasilitas ini sudah ada, tidak boleh sama.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.unique' => 'Deskripsi ini sudah digunakan oleh fasilitas lain, tidak boleh sama.',
        ]);

        $gambar = $fasilita->gambar;

        if ($request->hasFile('gambar')) {
            if ($fasilita->gambar) {
                Storage::disk('public')->delete($fasilita->gambar);
            }

            $gambar = $request->file('gambar')->store('fasilitas', 'public');
        }

        $fasilita->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if ($fasilita->gambar) {
            Storage::disk('public')->delete($fasilita->gambar);
        }

        $fasilita->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}