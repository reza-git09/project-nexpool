<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HargaTiket;
use Illuminate\Http\JsonResponse;

class HargaTiketApiController extends Controller
{
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
}