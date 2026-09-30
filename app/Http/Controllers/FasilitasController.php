<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FasilitasController extends Controller
{
    /* ---------- HELPER ---------- */

    private function poolId(): int
    {
        return (int) session('admin_pool_id');
    }

    private function authorizePool(Fasilitas $fasilita): void
    {
        if ((int) $fasilita->pool_id !== $this->poolId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    private function rules(?int $ignoreId = null): array
    {
        $unique = Rule::unique('fasilitas', 'nama_fasilitas')
            ->where(fn ($q) => $q->where('pool_id', $this->poolId()));

        if ($ignoreId) {
            $unique->ignore($ignoreId);
        }

        return [
            'nama_fasilitas' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255',
                $unique,
            ],
            'deskripsi' => ['required', 'string'],
            'gambar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    private function messages(): array
    {
        return [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.regex'    => 'Nama fasilitas hanya boleh berisi huruf dan spasi (tidak boleh menggunakan angka atau simbol).',
            'nama_fasilitas.unique'   => 'Nama fasilitas ini sudah ada di kolam renang ini, tidak boleh sama.',
            'deskripsi.required'      => 'Deskripsi wajib diisi.',
            'gambar.image'            => 'File yang dipilih harus berupa gambar.',
            'gambar.mimes'            => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max'              => 'Ukuran gambar maksimal 2 MB.',
        ];
    }

    private function deleteImage(?string $path): void
    {
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /* ---------- CRUD ---------- */

    public function index()
    {
        $fasilitas = Fasilitas::where('pool_id', $this->poolId())
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
        $request->validate($this->rules(), $this->messages());

        $gambar = $request->hasFile('gambar')
            ? $request->file('gambar')->store('fasilitas', 'public')
            : null;

        Fasilitas::create([
            'pool_id'        => $this->poolId(),
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi'      => $request->deskripsi,
            'gambar'         => $gambar,
            'status'         => 1,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Fasilitas $fasilita)
    {
        $this->authorizePool($fasilita);

        return view('fasilitas.show', compact('fasilita'));
    }

    public function edit(Fasilitas $fasilita)
    {
        $this->authorizePool($fasilita);

        return view('fasilitas.edit', compact('fasilita'));
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        $this->authorizePool($fasilita);

        $request->validate($this->rules($fasilita->id), $this->messages());

        $gambar = $fasilita->gambar;

        if ($request->hasFile('gambar')) {
            $this->deleteImage($fasilita->gambar);
            $gambar = $request->file('gambar')->store('fasilitas', 'public');
        }

        $fasilita->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi'      => $request->deskripsi,
            'gambar'         => $gambar,
        ]);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        $this->authorizePool($fasilita);

        $this->deleteImage($fasilita->gambar);
        $fasilita->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}