<?php

use App\Http\Controllers\Admin\LaporanPenjualanController;
use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\KolamRenangController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

// Halaman Form Pengajuan Admin
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Proses submit pengajuan (rate limit: 5 request per menit per IP)
Route::post('/register', [AdminApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.process');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| GANTI PASSWORD (Wajib untuk akun baru yang disetujui)
|--------------------------------------------------------------------------
*/

Route::get('/admin/ganti-password', [ChangePasswordController::class, 'show'])
    ->middleware(['admin'])
    ->name('admin.change-password');

Route::post('/admin/ganti-password', [ChangePasswordController::class, 'update'])
    ->middleware(['admin'])
    ->name('admin.change-password.process');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['admin', 'must.change.password'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN MASTER — MANAJEMEN PENGAJUAN
|--------------------------------------------------------------------------
*/

Route::get('/admin/master/pengajuan', [AdminApplicationController::class, 'index'])
    ->middleware(['admin', 'admin.master'])
    ->name('admin.master.applications.index');

Route::post('/admin/master/pengajuan/{app}/approve', [AdminApplicationController::class, 'approve'])
    ->middleware(['admin', 'admin.master'])
    ->name('admin.master.applications.approve');

Route::post('/admin/master/pengajuan/{app}/reject', [AdminApplicationController::class, 'reject'])
    ->middleware(['admin', 'admin.master'])
    ->name('admin.master.applications.reject');

/*
|--------------------------------------------------------------------------
| LAPORAN PENJUALAN
|--------------------------------------------------------------------------
*/

Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])
    ->middleware(['admin', 'must.change.password'])
    ->name('admin.laporan.penjualan');

Route::get('/laporan-penjualan/export', [LaporanPenjualanController::class, 'export'])
    ->middleware(['admin', 'must.change.password'])
    ->name('admin.laporan.penjualan.export');

/*
|--------------------------------------------------------------------------
| HARGA TIKET
|--------------------------------------------------------------------------
*/

Route::resource('harga-tiket', HargaTiketController::class)
    ->middleware(['admin', 'must.change.password']);

/*
|--------------------------------------------------------------------------
| FASILITAS
|--------------------------------------------------------------------------
*/

Route::resource('fasilitas', FasilitasController::class)
    ->middleware(['admin', 'must.change.password']);

/*
|--------------------------------------------------------------------------
| KOLAM RENANG
|--------------------------------------------------------------------------
*/

Route::resource('kolam-renang', KolamRenangController::class)
    ->middleware(['admin', 'must.change.password']);

/*
|--------------------------------------------------------------------------
| RESERVASI
|--------------------------------------------------------------------------
*/

// Halaman Reservasi Dikonfirmasi
Route::get('/reservasi/dikonfirmasi', [ReservasiController::class, 'dikonfirmasi'])
    ->middleware(['admin', 'must.change.password'])
    ->name('reservasi.dikonfirmasi');

// Resource Reservasi
Route::resource('reservasi', ReservasiController::class)
    ->middleware(['admin', 'must.change.password']);

/*
|--------------------------------------------------------------------------
| PROMO
|--------------------------------------------------------------------------
*/

Route::resource('promo', PromoController::class)
    ->middleware(['admin', 'must.change.password']);

/*
|--------------------------------------------------------------------------
| REVIEW
|--------------------------------------------------------------------------
*/

Route::resource('review', ReviewController::class)
    ->middleware(['admin', 'must.change.password']);
