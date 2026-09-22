<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KolamRenang;

class KolamRenangApiController extends Controller
{
    public function index()
    {
        $kolamRenang = KolamRenang::all();

        return response()->json([
            'success' => true,
            'message' => 'Data kolam renang berhasil diambil',
            'data' => $kolamRenang
        ]);
    }
}