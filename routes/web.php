<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KolamRenangController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReviewController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('admin')->name('dashboard');

Route::resource('harga-tiket', HargaTiketController::class)
    ->middleware('admin');
    
    Route::resource('fasilitas', FasilitasController::class)
    ->middleware('admin');

Route::resource('kolam-renang', KolamRenangController::class)
    ->middleware('admin');

Route::resource('reservasi', ReservasiController::class)
    ->middleware('admin');
    
Route::resource('promo', PromoController::class)
    ->middleware('admin');

Route::resource('review', ReviewController::class)
    ->middleware('admin');