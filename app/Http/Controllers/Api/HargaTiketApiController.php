<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HargaTiket;
use Illuminate\Http\JsonResponse;

class HargaTiketApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA HARGA TIKET
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        try {
            $data = HargaTiket::orderBy('pool_id')
                ->orderBy('kategori')
                ->orderBy('jenis_hari')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data harga tiket berhasil diambil',
                'data' => $data,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data harga tiket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL HARGA TIKET BERDASARKAN POOL
    |--------------------------------------------------------------------------
    */

    public function showByPool(string $pool_id): JsonResponse
    {
        try {

            $data = HargaTiket::where('pool_id', $pool_id)
                ->orderBy('kategori')
                ->orderBy('jenis_hari')
                ->get();

            if ($data->isEmpty()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Harga tiket untuk kolam renang ini belum tersedia.',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data harga tiket berhasil diambil',
                'data' => $data,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data harga tiket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}