<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    // Menampilkan promo sesuai pool admin yang login
    public function index()
    {
        $promo = Promo::where('pool_id', session('admin_pool_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('promo.index', compact('promo'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('promo.create');
    }

    // Menyimpan promo baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_diskon' => 'required|in:Persentase',
            'nilai_diskon' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
                function ($attribute, $value, $fail) use ($request) {
                    $mulai = new Carbon($request->tanggal_mulai);
                    $selesai = new Carbon($value);
                    if ($selesai->greaterThan($mulai->copy()->addMonth())) {
                        $fail('Tanggal Selesai tidak boleh lebih dari 1 bulan setelah Tanggal Mulai.');
                    }
                },
            ],
        ], [
            'nilai_diskon.max' => 'Nilai diskon persentase tidak boleh melebihi 100%.',
            'tanggal_mulai.after_or_equal' => 'Tanggal Mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.after' => 'Tanggal Selesai harus minimal 1 hari setelah Tanggal Mulai.',
        ]);

        Promo::create([
            'pool_id' => session('admin_pool_id'),
            'nama_promo' => $request->nama_promo,
            'deskripsi' => $request->deskripsi,
            'jenis_diskon' => 'Persentase',
            'nilai_diskon' => $request->nilai_diskon,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => true,
        ]);

        return redirect()
            ->route('promo.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    // Menampilkan detail promo
    public function show(Promo $promo)
    {
        if ($promo->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('promo.show', compact('promo'));
    }

    // Menampilkan form edit
    public function edit(Promo $promo)
    {
        if ($promo->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('promo.edit', compact('promo'));
    }

    // Memperbarui promo
    public function update(Request $request, Promo $promo)
    {
        if ($promo->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_diskon' => 'required|in:Persentase',
            'nilai_diskon' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
                function ($attribute, $value, $fail) use ($request) {
                    $mulai = new Carbon($request->tanggal_mulai);
                    $selesai = new Carbon($value);
                    if ($selesai->greaterThan($mulai->copy()->addMonth())) {
                        $fail('Tanggal Selesai tidak boleh lebih dari 1 bulan setelah Tanggal Mulai.');
                    }
                },
            ],
        ], [
            'nilai_diskon.max' => 'Nilai diskon persentase tidak boleh melebihi 100%.',
            'tanggal_mulai.after_or_equal' => 'Tanggal Mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.after' => 'Tanggal Selesai harus minimal 1 hari setelah Tanggal Mulai.',
        ]);

        $promo->update([
            'nama_promo' => $request->nama_promo,
            'deskripsi' => $request->deskripsi,
            'jenis_diskon' => 'Persentase',
            'nilai_diskon' => $request->nilai_diskon,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => true,
        ]);

        return redirect()
            ->route('promo.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    // Menghapus promo
    public function destroy(Promo $promo)
    {
        if ($promo->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $promo->delete();

        return redirect()
            ->route('promo.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}
