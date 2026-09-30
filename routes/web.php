<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KolamRenangController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware('admin')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| HARGA TIKET
|--------------------------------------------------------------------------
*/

Route::resource('harga-tiket', HargaTiketController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| FASILITAS
|--------------------------------------------------------------------------
*/

Route::resource('fasilitas', FasilitasController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| KOLAM RENANG
|--------------------------------------------------------------------------
*/

Route::resource('kolam-renang', KolamRenangController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| RESERVASI
|--------------------------------------------------------------------------
*/

// Halaman Reservasi Dikonfirmasi
Route::get('/reservasi/dikonfirmasi', [ReservasiController::class, 'dikonfirmasi'])
    ->middleware('admin')
    ->name('reservasi.dikonfirmasi');

// Resource Reservasi
Route::resource('reservasi', ReservasiController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| PROMO
|--------------------------------------------------------------------------
*/

Route::resource('promo', PromoController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| REVIEW
|--------------------------------------------------------------------------
*/

Route::resource('review', ReviewController::class)
    ->middleware('admin');
