<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Menampilkan review hanya milik pool admin yang sedang login.
     */
    public function index()
    {
        $review = Review::where('pool_id', session('admin_pool_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('review.index', compact('review'));
    }

    /**
     * Menampilkan detail review.
     */
    public function show(Review $review)
    {
        // Pastikan review hanya bisa dilihat oleh
        // admin dari pool yang sesuai.
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('review.show', compact('review'));
    }

    /**
     * Menampilkan form untuk membalas review.
     */
    public function edit(Review $review)
    {
        // Pastikan review milik pool admin yang login.
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('review.edit', compact('review'));
    }

    /**
     * Menyimpan balasan admin terhadap review.
     */
    public function update(Request $request, Review $review)
    {
        // Pastikan review milik pool admin yang login.
        if ($review->pool_id !== session('admin_pool_id')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'balasan_admin' => 'required|string',
        ]);

        $review->update([
            'balasan_admin' => $request->balasan_admin,
            'status' => 'Dibalas',
        ]);

        return redirect()
            ->route('review.index')
            ->with('success', 'Balasan review berhasil disimpan.');
    }
}