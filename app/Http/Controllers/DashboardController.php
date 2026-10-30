<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // ID kolam renang milik admin yang sedang login
        $poolId = session('admin_pool_id');

        /*
        |--------------------------------------------------------------------------
        | TOTAL PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        $pengunjungQuery = DB::table('pengunjung');

        if (
            $poolId &&
            Schema::hasColumn('pengunjung', 'pool_id')
        ) {
            $pengunjungQuery->where('pool_id', $poolId);
        }

        $totalPengunjung = $pengunjungQuery->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL RESERVASI / TIKET
        |--------------------------------------------------------------------------
        */

        $reservasiQuery = DB::table('reservasis');

        if (
            $poolId &&
            Schema::hasColumn('reservasis', 'pool_id')
        ) {
            $reservasiQuery->where('pool_id', $poolId);
        }

        $totalTiket = $reservasiQuery->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PROMO AKTIF
        |--------------------------------------------------------------------------
        */

        $promoQuery = DB::table('promos');

        // Filter berdasarkan kolam admin yang sedang login
        if (
            $poolId &&
            Schema::hasColumn('promos', 'pool_id')
        ) {
            $promoQuery->where('pool_id', $poolId);
        }

        // Status 1 berarti promo aktif
        if (Schema::hasColumn('promos', 'status')) {
            $promoQuery->where('status', 1);
        }

        // Hitung promo yang masih dalam periode berlaku
        if (
            Schema::hasColumn('promos', 'tanggal_mulai') &&
            Schema::hasColumn('promos', 'tanggal_selesai')
        ) {
            $tanggalHariIni = now('Asia/Jakarta')->toDateString();

            $promoQuery
                ->whereDate('tanggal_mulai', '<=', $tanggalHariIni)
                ->whereDate('tanggal_selesai', '>=', $tanggalHariIni);
        }

        $promoAktif = $promoQuery->count();

        /*
        |--------------------------------------------------------------------------
        | RESERVASI TERBARU
        |--------------------------------------------------------------------------
        */

        $reservasiTerbaruQuery = DB::table('reservasis');

        if (
            $poolId &&
            Schema::hasColumn('reservasis', 'pool_id')
        ) {
            $reservasiTerbaruQuery->where('pool_id', $poolId);
        }

        if (Schema::hasColumn('reservasis', 'created_at')) {
            $reservasiTerbaruQuery->orderByDesc('created_at');
        } elseif (Schema::hasColumn('reservasis', 'id_reservasi')) {
            $reservasiTerbaruQuery->orderByDesc('id_reservasi');
        }

        $reservasiTerbaru = $reservasiTerbaruQuery
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalPengunjung',
            'totalTiket',
            'promoAktif',
            'reservasiTerbaru'
        ));
    }
}