<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HargaTiketApiController;
use App\Http\Controllers\Api\KolamRenangApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ReservasiApiController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API berhasil'
    ]);
});

Route::get('/harga-tiket', [
    HargaTiketApiController::class,
    'index'
]);

Route::get('/kolam-renang', [
    KolamRenangApiController::class,
    'index'
]);

// =========================================================
// REVIEW
// =========================================================

Route::get('/reviews', [
    ReviewApiController::class,
    'index'
]);

Route::post('/reviews', [
    ReviewApiController::class,
    'store'
]);

Route::get('/reviews/{id}', [
    ReviewApiController::class,
    'show'
]);

// =========================================================
// RESERVASI
// =========================================================

Route::get('/reservasi', [
    ReservasiApiController::class,
    'index'
]);

Route::post('/reservasi', [
    ReservasiApiController::class,
    'store'
]);

Route::get('/reservasi/{id}', [
    ReservasiApiController::class,
    'show'
]);

Route::get('/reservasi/kode/{kode}', [
    ReservasiApiController::class,
    'showByKode'
]);