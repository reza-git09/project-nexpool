<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HargaTiketApiController;
use App\Http\Controllers\Api\KolamRenangApiController;
use App\Http\Controllers\Api\ReviewApiController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API berhasil'
    ]);
});

Route::get('/harga-tiket', [HargaTiketApiController::class, 'index']);

Route::get('/kolam-renang', [KolamRenangApiController::class, 'index']);

Route::get('/reviews', [ReviewApiController::class, 'index']);
Route::post('/reviews', [ReviewApiController::class, 'store']);
Route::get('/reviews/{id}', [ReviewApiController::class, 'show']);