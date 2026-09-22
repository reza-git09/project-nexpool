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
        // 1. Validasi ketat: Murni angka, 4-5 digit, rentang Rp1.000 - Rp99.999
        $request->validate([
            'kategori' => 'required|in:Dewasa,Anak',
            'jenis_hari' => 'required|in:Weekday,Weekend',
            'harga' => [
                'required',
                'regex:/^[0-9]+$/',
                'digits_between:4,5',
                'integer',
                'min:1000',
                'max:99999',
            ],
        ], [
            'kategori.required' => 'Kategori tiket wajib dipilih.',
            'kategori.in' => 'Kategori tiket tidak valid.',
            'jenis_hari.required' => 'Jenis hari wajib dipilih.',
            'jenis_hari.in' => 'Jenis hari tidak valid.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.regex' => 'Harga tiket hanya boleh diisi oleh angka saja (tanpa huruf, titik, atau koma).',
            'harga.digits_between' => 'Harga tiket harus berjumlah 4 hingga 5 digit angka.',
            'harga.integer' => 'Harga tiket harus berupa bilangan bulat.',
            'harga.min' => 'Harga tiket minimal Rp1.000.',
            'harga.max' => 'Harga tiket maksimal Rp99.999.',
        ]);

        // 2. Cek kombinasi ganda (pool_id + kategori + jenis_hari)
        $isDuplicate = HargaTiket::where('pool_id', session('admin_pool_id'))
            ->where('kategori', $request->kategori)
            ->where('jenis_hari', $request->jenis_hari)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors(['kategori' => "Data harga tiket untuk kategori '{$request->kategori}' pada hari '{$request->jenis_hari}' sudah tersedia sebelumnya."]);
        }

        // 3. Simpan data
        HargaTiket::create([
            'pool_id' => session('admin_pool_id'),
            'kategori' => $request->kategori,
            'jenis_hari' => $request->jenis_hari,
            'harga' => (int) $request->harga,
        ]);

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(HargaTiket $hargaTiket)
    {
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Menghilangkan format desimal (.00) saat dikirim ke form edit
        $hargaTiket->harga = (int) $hargaTiket->harga;

        return view('harga_tiket.edit', compact('hargaTiket'));
    }

    // Memperbarui harga tiket
    public function update(Request $request, HargaTiket $hargaTiket)
    {
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // 1. Validasi ketat input edit
        $request->validate([
            'kategori' => 'required|in:Dewasa,Anak',
            'jenis_hari' => 'required|in:Weekday,Weekend',
            'harga' => [
                'required',
                'regex:/^[0-9]+$/',
                'digits_between:4,5',
                'integer',
                'min:1000',
                'max:99999',
            ],
        ], [
            'kategori.required' => 'Kategori tiket wajib dipilih.',
            'kategori.in' => 'Kategori tiket tidak valid.',
            'jenis_hari.required' => 'Jenis hari wajib dipilih.',
            'jenis_hari.in' => 'Jenis hari tidak valid.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.regex' => 'Harga tiket hanya boleh diisi oleh angka saja (tanpa huruf, titik, atau koma).',
            'harga.digits_between' => 'Harga tiket harus berjumlah 4 hingga 5 digit angka.',
            'harga.integer' => 'Harga tiket harus berupa bilangan bulat.',
            'harga.min' => 'Harga tiket minimal Rp1.000.',
            'harga.max' => 'Harga tiket maksimal Rp99.999.',
        ]);

        // 2. Cek apakah kombinasi baru sudah ada di record lain (kecuali data ini sendiri)
        $isDuplicate = HargaTiket::where('pool_id', session('admin_pool_id'))
            ->where('kategori', $request->kategori)
            ->where('jenis_hari', $request->jenis_hari)
            ->where('id', '!=', $hargaTiket->id)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors(['kategori' => "Data harga tiket untuk kategori '{$request->kategori}' pada hari '{$request->jenis_hari}' sudah tersedia sebelumnya."]);
        }

        // 3. Update database
        $hargaTiket->update([
            'kategori' => $request->kategori,
            'jenis_hari' => $request->jenis_hari,
            'harga' => (int) $request->harga,
        ]);

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil diperbarui.');
    }

    // Menghapus harga tiket
    public function destroy(HargaTiket $hargaTiket)
    {
        if ($hargaTiket->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $hargaTiket->delete();

        return redirect()
            ->route('harga-tiket.index')
            ->with('success', 'Harga tiket berhasil dihapus.');
    }
}
