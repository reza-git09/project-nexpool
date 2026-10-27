
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HargaTiketApiController;
use App\Http\Controllers\Api\KolamRenangApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ReservasiApiController;
use App\Http\Controllers\Api\PengunjungApiController;
use App\Http\Controllers\Api\FasilitasApiController;

// =========================================================
// TEST API
// =========================================================
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// Route Register API
Route::post('/register', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => $validator->errors()->first(),
            'errors'  => $validator->errors()
        ], 422);
    }

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status'  => 'success',
        'message' => 'Registrasi berhasil',
        'token'   => $token,
        'data'    => $user
    ], 201);
});

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'data'   => $request->user()
    ]);
});

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API berhasil'
    ]);
});

// =========================================================
// HARGA TIKET
// =========================================================

Route::get('/harga-tiket', [
    HargaTiketApiController::class,
    'index'
]);

Route::get('/harga-tiket/{pool_id}', [
    HargaTiketApiController::class,
    'showByPool'
]);

// =========================================================
// KOLAM RENANG
// =========================================================

Route::get('/kolam-renang', [
    KolamRenangApiController::class,
    'index'
]);
Route::get('/harga-tiket', [HargaTiketApiController::class, 'index']);
Route::get('/kolam-renang', [KolamRenangApiController::class, 'index']);

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
