<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use App\Models\KolamRenang;
use App\Models\HargaTiket;
use App\Models\AdminApplication;

class AdminMasterController extends Controller
{
    public function index()
    {
        $totalPengunjung = User::where('role', 'pengunjung')->count();

        $totalAdmin = User::where('role', 'admin_pool')->count();

        $totalKolam = KolamRenang::count();

        $totalTransaksi = Reservasi::count();

        $totalReservasi = Reservasi::count();

        $totalHargaTiket = HargaTiket::count();

        $totalPengajuan = AdminApplication::where(
            'status',
            AdminApplication::STATUS_PENDING
        )->count();

        $pembayaranBerhasil = Reservasi::where(
            'status_pembayaran',
            'paid'
        )->count();

        $pembayaranPending = Reservasi::where(
            'status_pembayaran',
            'pending'
        )->count();

        $pembayaranGagal = Reservasi::whereIn(
            'status_pembayaran',
            ['failed', 'expire', 'expired', 'deny', 'cancel']
        )->count();

        $totalPendapatan = Reservasi::where(
            'status_pembayaran',
            'paid'
        )->sum('total_harga');

        return view('admin_master.dashboard', compact(
            'totalPengunjung',
            'totalAdmin',
            'totalKolam',
            'totalTransaksi',
            'totalReservasi',
            'totalHargaTiket',
            'totalPengajuan',
            'pembayaranBerhasil',
            'pembayaranPending',
            'pembayaranGagal',
            'totalPendapatan'
        ));
    }
}
