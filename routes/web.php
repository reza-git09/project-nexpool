<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KolamRenangController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| AUTHENTICATION (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('register.process');

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
