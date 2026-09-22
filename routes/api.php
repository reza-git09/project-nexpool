<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamRenangApiController;
use App\Http\Controllers\Api\HargaTiketApiController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API NEXPOOL berhasil terhubung!'
    ]);
});

Route::get('/kolam-renang', [KolamRenangApiController::class, 'index']);

Route::get('/harga-tiket', [HargaTiketApiController::class, 'index']);