
<?php

use App\Http\Controllers\Admin\LaporanPenjualanController;
use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AdminMasterTransaksiController;
use App\Http\Controllers\Api\AdminMasterTransaksiApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\KolamRenangController;
use App\Http\Controllers\PenarikanSaldoController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| GANTI PASSWORD
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
| DASHBOARD ADMIN KOLAM
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['admin', 'must.change.password'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN MASTER - DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin-master/dashboard', [AdminMasterController::class, 'index'])
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN MASTER - TRANSAKSI PEMBAYARAN (WEB)
|--------------------------------------------------------------------------
*/

Route::get('/admin-master/transaksi', [
    AdminMasterTransaksiController::class,
    'index',
])
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.transaksi.index');

/*
|--------------------------------------------------------------------------
| ADMIN MASTER - MANAJEMEN PENGAJUAN
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
| PENARIKAN SALDO ADMIN KOLAM
|--------------------------------------------------------------------------
*/

Route::get('/penarikan-saldo', [PenarikanSaldoController::class, 'index'])
    ->middleware(['admin', 'must.change.password'])
    ->name('penarikan-saldo.index');

Route::post('/penarikan-saldo', [PenarikanSaldoController::class, 'store'])
    ->middleware(['admin', 'must.change.password'])
    ->name('penarikan-saldo.store');

/*
|--------------------------------------------------------------------------
| PENCAIRAN SALDO ADMIN MASTER
|--------------------------------------------------------------------------
*/

Route::get('/admin-master/penarikan-saldo', [PenarikanSaldoController::class, 'indexForAdminMaster'])
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.penarikan-saldo.index');

Route::post('/admin-master/penarikan-saldo/{withdrawal}/cairkan', [PenarikanSaldoController::class, 'complete'])
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.penarikan-saldo.complete');

Route::post('/admin-master/penarikan-saldo/{withdrawal}/tolak', [PenarikanSaldoController::class, 'reject'])
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.penarikan-saldo.reject');

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

Route::get('/reservasi/dikonfirmasi', [ReservasiController::class, 'dikonfirmasi'])
    ->middleware(['admin', 'must.change.password'])
    ->name('reservasi.dikonfirmasi');

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

/*
|--------------------------------------------------------------------------
| ADMIN MASTER - TRANSAKSI API
|--------------------------------------------------------------------------
*/

Route::prefix('admin/master/api')
    ->middleware(['admin', 'admin.master', 'must.change.password'])
    ->name('admin.master.api.')
    ->group(function () {

        Route::get('/dashboard', [
            AdminMasterTransaksiApiController::class,
            'dashboard',
        ])->name('dashboard');

        Route::get('/transaksi', [
            AdminMasterTransaksiApiController::class,
            'index',
        ])->name('transaksi.index');

        Route::get('/transaksi/{id}', [
            AdminMasterTransaksiApiController::class,
            'show',
        ])->name('transaksi.show');

        Route::get('/kolam', [
            AdminMasterTransaksiApiController::class,
            'kolam',
        ])->name('kolam');
    });
