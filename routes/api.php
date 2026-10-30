<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HargaTiketApiController;
use App\Http\Controllers\Api\KolamRenangApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ReservasiApiController;
use App\Http\Controllers\Api\PengunjungApiController;
use App\Http\Controllers\Api\FasilitasApiController;
use App\Http\Controllers\Api\PromoApiController;

// =========================================================
// AUTH PENGUNJUNG (tabel: pengunjung)
// =========================================================
Route::post('/register', [PengunjungApiController::class, 'register']);
Route::post('/login', [PengunjungApiController::class, 'login']);
Route::post('/check-account', [PengunjungApiController::class, 'checkAccount']);
Route::post('/reset-password', [PengunjungApiController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'data'   => $request->user()
    ]);
});

// =========================================================
// TEST API
// =========================================================
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API berhasil'
    ]);
});

// =========================================================
// HARGA TIKET
// =========================================================
Route::get('/harga-tiket', [HargaTiketApiController::class, 'index']);
Route::get('/harga-tiket/{pool_id}', [HargaTiketApiController::class, 'showByPool']);

// =========================================================
// KOLAM RENANG
// =========================================================
Route::get('/kolam-renang', [KolamRenangApiController::class, 'index']);

// =========================================================
// FASILITAS (diisi lewat admin, tampil di aplikasi)
// =========================================================
Route::get('/fasilitas', [FasilitasApiController::class, 'index']);
Route::get('/fasilitas/{id}', [FasilitasApiController::class, 'show']);

// =========================================================
// PROMO (diisi lewat admin, tampil di aplikasi)
// =========================================================
Route::get('/promo', [PromoApiController::class, 'index']);

// =========================================================
// REVIEW
// =========================================================
Route::get('/reviews', [ReviewApiController::class, 'index']);
Route::post('/reviews', [ReviewApiController::class, 'store']);
Route::get('/reviews/{id}', [ReviewApiController::class, 'show']);

// =========================================================
// RESERVASI
// =========================================================
Route::get('/reservasi', [ReservasiApiController::class, 'index']);
Route::post('/reservasi', [ReservasiApiController::class, 'store']);
Route::get('/reservasi/{id}', [ReservasiApiController::class, 'show']);
Route::get('/reservasi/kode/{kode}', [ReservasiApiController::class, 'showByKode']);