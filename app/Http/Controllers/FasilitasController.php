<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $poolId = session('admin_pool_id');

        $request->validate([
            'nama_fasilitas' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',

                Rule::unique('fasilitas', 'nama_fasilitas')
                    ->where(function ($query) use ($poolId) {
                        return $query->where('pool_id', $poolId);
                    }),
            ],

            'deskripsi' => 'required|string',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required|boolean',
        ], [
            'nama_fasilitas.required' =>
                'Nama fasilitas wajib diisi.',

            'nama_fasilitas.regex' =>
                'Nama fasilitas hanya boleh berisi huruf dan spasi (tidak boleh menggunakan angka atau simbol).',

            'nama_fasilitas.unique' =>
                'Nama fasilitas ini sudah ada di kolam renang ini, tidak boleh sama.',

            'deskripsi.required' =>
                'Deskripsi wajib diisi.',

            'gambar.image' =>
                'File yang dipilih harus berupa gambar.',

            'gambar.mimes' =>
                'Format gambar harus JPG, JPEG, PNG, atau WEBP.',

            'gambar.max' =>
                'Ukuran gambar maksimal 2 MB.',

            'status.required' =>
                'Status fasilitas wajib dipilih.',
        ]);

        $gambar = null;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD GAMBAR BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                ->store('fasilitas', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA FASILITAS
        |--------------------------------------------------------------------------
        */

        Fasilitas::create([
            'pool_id' => $poolId,
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
        /*
        |--------------------------------------------------------------------------
        | CEK AKSES POOL
        |--------------------------------------------------------------------------
        */

        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.show', compact('fasilita'));
    }

    public function edit(Fasilitas $fasilita)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK AKSES POOL
        |--------------------------------------------------------------------------
        */

        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('fasilitas.edit', compact('fasilita'));
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK AKSES POOL
        |--------------------------------------------------------------------------
        */

        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $poolId = session('admin_pool_id');

        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA
        |--------------------------------------------------------------------------
        |
        | Pada halaman edit yang kamu kirim tidak ada input status.
        | Jadi status tidak divalidasi dari request.
        |
        */

        $request->validate([
            'nama_fasilitas' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',

                Rule::unique('fasilitas', 'nama_fasilitas')
                    ->where(function ($query) use ($poolId) {
                        return $query->where('pool_id', $poolId);
                    })
                    ->ignore($fasilita->id),
            ],

            'deskripsi' => 'required|string',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_fasilitas.required' =>
                'Nama fasilitas wajib diisi.',

            'nama_fasilitas.regex' =>
                'Nama fasilitas hanya boleh berisi huruf dan spasi (tidak boleh menggunakan angka atau simbol).',

            'nama_fasilitas.unique' =>
                'Nama fasilitas ini sudah ada di kolam renang ini, tidak boleh sama.',

            'deskripsi.required' =>
                'Deskripsi wajib diisi.',

            'gambar.image' =>
                'File yang dipilih harus berupa gambar.',

            'gambar.mimes' =>
                'Format gambar harus JPG, JPEG, PNG, atau WEBP.',

            'gambar.max' =>
                'Ukuran gambar maksimal 2 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN GAMBAR LAMA
        |--------------------------------------------------------------------------
        */

        $gambar = $fasilita->gambar;

        /*
        |--------------------------------------------------------------------------
        | JIKA USER MEMILIH GAMBAR BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika memang ada
            if (
                !empty($fasilita->gambar) &&
                Storage::disk('public')->exists($fasilita->gambar)
            ) {
                Storage::disk('public')->delete(
                    $fasilita->gambar
                );
            }

            // Simpan gambar baru
            $gambar = $request->file('gambar')
                ->store('fasilitas', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $fasilita->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,

            // Status lama tetap dipertahankan
            'status' => $fasilita->status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK AKSES POOL
        |--------------------------------------------------------------------------
        */

        if ($fasilita->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS GAMBAR
        |--------------------------------------------------------------------------
        */

        if (
            !empty($fasilita->gambar) &&
            Storage::disk('public')->exists($fasilita->gambar)
        ) {
            Storage::disk('public')->delete(
                $fasilita->gambar
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */

        $fasilita->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
