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
        | GRAFIK TIKET TUJUH HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartEndDate = now('Asia/Jakarta')->startOfDay();
        $chartStartDate = $chartEndDate->copy()->subDays(6);
        $chartReservationsQuery = DB::table('reservasis')
            ->whereBetween('tanggal_kunjungan', [
                $chartStartDate->toDateString(),
                $chartEndDate->toDateString(),
            ]);

        if ($poolId && Schema::hasColumn('reservasis', 'pool_id')) {
            $chartReservationsQuery->where('pool_id', $poolId);
        }

        if (Schema::hasColumn('reservasis', 'status_reservasi')) {
            $chartReservationsQuery->whereIn('status_reservasi', [
                'Dikonfirmasi',
                'Selesai',
            ]);
        }

        $chartTicketTotalsByDate = $chartReservationsQuery
            ->select('tanggal_kunjungan')
            ->selectRaw('SUM(jumlah_dewasa + jumlah_anak) as total_tiket')
            ->groupBy('tanggal_kunjungan')
            ->pluck('total_tiket', 'tanggal_kunjungan');

        $weekdayLabels = [
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
            7 => 'Min',
        ];

        $chartDays = [];

        for ($daysAgo = 0; $daysAgo < 7; $daysAgo++) {
            $date = $chartStartDate->copy()->addDays($daysAgo);
            $dateKey = $date->toDateString();
            $ticketCount = (int) ($chartTicketTotalsByDate[$dateKey] ?? 0);

            $chartDays[] = [
                'label' => $weekdayLabels[$date->dayOfWeekIso],
                'date' => $date->format('d/m'),
                'day_type' => $date->isWeekend() ? 'weekend' : 'weekday',
                'total_tiket' => $ticketCount,
            ];
        }

        $chartTotalTickets = array_sum(array_column($chartDays, 'total_tiket'));
        $chartMaxTickets = max(1, max(array_column($chartDays, 'total_tiket')));

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
            'chartDays',
            'chartMaxTickets',
            'chartTotalTickets',
            'promoAktif',
            'reservasiTerbaru'
        ));
    }
}
