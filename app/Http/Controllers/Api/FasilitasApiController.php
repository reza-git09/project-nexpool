<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasApiController extends Controller
{
    /**
     * GET /api/fasilitas
     * Menampilkan daftar fasilitas.
     */
    public function index(Request $request)
    {
        $query = Fasilitas::query();

        // Filter fasilitas berdasarkan kolam renang
        if ($request->filled('pool_id')) {
            $query->where('pool_id', $request->pool_id);
        }

        // Tampilkan hanya fasilitas yang aktif
        $query->where('status', true);

        $fasilitas = $query
            ->orderBy('nama_fasilitas')
            ->get()
            ->map(function ($item) {
                $item->gambar_url = $item->gambar
                    ? asset('storage/' . $item->gambar)
                    : null;

                return $item;
            });

        return response()->json([
            'success' => true,
            'message' => 'Data fasilitas berhasil diambil.',
            'data' => $fasilitas,
        ]);
    }

    /**
     * GET /api/fasilitas/{id}
     * Menampilkan detail fasilitas.
     */
    public function show($id)
    {
        $fasilitas = Fasilitas::where('status', true)
            ->find($id);

        if (!$fasilitas) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        $fasilitas->gambar_url = $fasilitas->gambar
            ? asset('storage/' . $fasilitas->gambar)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Detail fasilitas berhasil diambil.',
            'data' => $fasilitas,
        ]);
    }
}