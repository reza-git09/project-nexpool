<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Menampilkan review sesuai pool admin yang login
    public function index()
    {
        $review = Review::where('pool_id', session('admin_pool_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('review.index', compact('review'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('review.create');
    }

    // Menyimpan review baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
            'balasan_admin' => 'nullable|string',
            'status' => 'required|in:Menunggu,Dibalas',
        ]);

        Review::create([
            'pool_id' => session('admin_pool_id'),
            'nama_pengunjung' => $request->nama_pengunjung,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'balasan_admin' => $request->balasan_admin,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('review.index')
            ->with('success', 'Review berhasil ditambahkan.');
    }

    // Menampilkan detail review
    public function show(Review $review)
    {
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('review.show', compact('review'));
    }

    // Menampilkan form edit
    public function edit(Review $review)
    {
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('review.edit', compact('review'));
    }

    // Memperbarui review
    public function update(Request $request, Review $review)
    {
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
            'balasan_admin' => 'nullable|string',
            'status' => 'required|in:Menunggu,Dibalas',
        ]);

        $review->update([
            'nama_pengunjung' => $request->nama_pengunjung,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'balasan_admin' => $request->balasan_admin,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('review.index')
            ->with('success', 'Review berhasil diperbarui.');
    }

    // Menghapus review
    public function destroy(Review $review)
    {
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $review->delete();

        return redirect()
            ->route('review.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}