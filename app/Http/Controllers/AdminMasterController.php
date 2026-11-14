<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\HargaTiket;
use App\Models\KolamRenang;
use App\Models\PenarikanSaldo;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMasterController extends Controller
{
    public function index()
    {
        $kolam = KolamRenang::orderBy('nama_kolam')
            ->get(['pool_id', 'nama_kolam']);

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

        $totalPenarikan = PenarikanSaldo::where(
            'status',
            PenarikanSaldo::STATUS_TERKONFIRMASI
        )->sum('amount');

        $totalPendapatan = max((float) $totalPendapatan - (float) $totalPenarikan, 0.0);

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
            'totalPendapatan',
            'kolam'
        ));
    }

    public function grafikPendapatan(Request $request)
    {
        $validated = $request->validate([
            'pool_id' => ['nullable', 'string', 'exists:kolam_renang,pool_id'],
            'periode' => ['required', 'in:7_hari,30_hari,bulan_ini'],
        ]);

        $timezone = 'Asia/Jakarta';
        $today = Carbon::now($timezone)->startOfDay();
        $start = match ($validated['periode']) {
            '7_hari' => $today->copy()->subDays(6),
            '30_hari' => $today->copy()->subDays(29),
            'bulan_ini' => $today->copy()->startOfMonth(),
        };
        $endExclusive = $today->copy()->addDay();

        $effectiveAt = "CASE WHEN status_pembayaran = 'paid' AND paid_at IS NOT NULL
            THEN paid_at ELSE created_at END";
        $dateExpression = match (DB::connection()->getDriverName()) {
            'mysql' => "DATE(CONVERT_TZ(($effectiveAt), @@session.time_zone, '+07:00'))",
            'sqlite' => "DATE(($effectiveAt), '+7 hours')",
            'pgsql' => "CAST((($effectiveAt) AT TIME ZONE 'UTC' AT TIME ZONE 'Asia/Jakarta') AS DATE)",
            default => throw new \LogicException('Database driver tidak didukung untuk grafik pendapatan.'),
        };

        $query = Reservasi::query()
            ->where('status_pembayaran', 'paid')
            ->whereRaw("$dateExpression >= ?", [$start->toDateString()])
            ->whereRaw("$dateExpression < ?", [$endExclusive->toDateString()]);

        if (! empty($validated['pool_id'])) {
            $query->where('pool_id', $validated['pool_id']);
        }

        $total = (float) (clone $query)->sum('total_harga');
        $aggregates = $query
            ->selectRaw("$dateExpression as tanggal, COALESCE(SUM(total_harga), 0) as pendapatan")
            ->groupByRaw($dateExpression)
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $labels = [];
        $values = [];
        for ($date = $start->copy(); $date->lt($endExclusive); $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $key;
            $values[] = (float) ($aggregates->get($key)->pendapatan ?? 0);
        }

        $hasTransactions = $aggregates->isNotEmpty();
        $poolName = empty($validated['pool_id'])
            ? 'Semua kolam'
            : KolamRenang::where('pool_id', $validated['pool_id'])->value('nama_kolam');
        $periodName = [
            '7_hari' => '7 hari terakhir',
            '30_hari' => '30 hari terakhir',
            'bulan_ini' => 'bulan ini',
        ][$validated['periode']];

        return response()->json([
            'labels' => $hasTransactions ? $labels : [],
            'values' => $hasTransactions ? $values : [],
            'total' => $total,
            'has_transactions' => $hasTransactions,
            'pool_name' => $poolName,
            'period_name' => $periodName,
        ]);
    }
}
