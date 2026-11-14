<?php

namespace App\Http\Controllers;

use App\Models\KolamRenang;
use App\Models\PenarikanSaldo;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMasterTransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Query seluruh transaksi pembayaran Midtrans
        $query = Reservasi::with('kolamRenang');

        // Filter kode reservasi
        if ($request->filled('search')) {
            $query->where(
                'kode_reservasi',
                'like',
                '%'.$request->search.'%'
            );
        }

        // Filter kolam berdasarkan pool_id
        if ($request->filled('pool_id')) {
            $query->where('pool_id', $request->pool_id);
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where(
                'status_pembayaran',
                $request->status_pembayaran
            );
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate(
                DB::raw(
                    "CASE WHEN status_pembayaran = 'paid' AND paid_at IS NOT NULL
                    THEN paid_at ELSE created_at END"
                ),
                $request->tanggal
            );
        }

        // Data transaksi dengan pagination
        $transaksi = $query
            ->orderByRaw("
                CASE WHEN status_pembayaran = 'paid' AND paid_at IS NOT NULL
                THEN paid_at ELSE created_at END DESC
            ")
            ->paginate(15)
            ->withQueryString();

        // Daftar seluruh kolam
        $kolam = KolamRenang::orderBy('nama_kolam')->get();

        // Statistik berdasarkan filter yang sama
        $statistikQuery = clone $query;

        $statistik = $statistikQuery
            ->reorder()
            ->selectRaw("
                COUNT(*) as total_transaksi,
                SUM(CASE WHEN status_pembayaran = 'paid'
                    THEN 1 ELSE 0 END) as transaksi_berhasil,
                SUM(CASE WHEN status_pembayaran = 'pending'
                    THEN 1 ELSE 0 END) as transaksi_pending,
                SUM(CASE WHEN status_pembayaran = 'expired'
                    THEN 1 ELSE 0 END) as transaksi_expired,
                COALESCE(SUM(
                    CASE WHEN status_pembayaran = 'paid'
                    THEN total_harga ELSE 0 END
                ), 0) as total_pendapatan
            ")
            ->first();

        $statistik->total_pendapatan = Reservasi::query()
            ->where('status_pembayaran', 'paid')
            ->sum('total_harga');

        $pendapatanPerKolam = Reservasi::query()
            ->where('status_pembayaran', 'paid')
            ->select('pool_id')
            ->selectRaw('COUNT(*) as transaksi_berhasil, COALESCE(SUM(total_harga), 0) as total_pendapatan')
            ->groupBy('pool_id');

        $penarikanPerKolam = PenarikanSaldo::query()
            ->where('status', PenarikanSaldo::STATUS_TERKONFIRMASI)
            ->select('pool_id')
            ->selectRaw('COALESCE(SUM(amount), 0) as sudah_ditarik')
            ->groupBy('pool_id');

        $pendapatanKolam = KolamRenang::query()
            ->leftJoinSub($pendapatanPerKolam, 'pendapatan', 'kolam_renang.pool_id', '=', 'pendapatan.pool_id')
            ->leftJoinSub($penarikanPerKolam, 'penarikan', 'kolam_renang.pool_id', '=', 'penarikan.pool_id')
            ->select('kolam_renang.pool_id', 'kolam_renang.nama_kolam')
            ->selectRaw('
                COALESCE(pendapatan.transaksi_berhasil, 0) as transaksi_berhasil,
                COALESCE(pendapatan.total_pendapatan, 0) as total_pendapatan,
                COALESCE(penarikan.sudah_ditarik, 0) as sudah_ditarik,
                CASE
                    WHEN COALESCE(pendapatan.total_pendapatan, 0) > COALESCE(penarikan.sudah_ditarik, 0)
                    THEN COALESCE(pendapatan.total_pendapatan, 0) - COALESCE(penarikan.sudah_ditarik, 0)
                    ELSE 0
                END as saldo_tersisa
            ')
            ->orderBy('kolam_renang.nama_kolam')
            ->get();

        $peringkatKolam = $pendapatanKolam
            ->filter(static fn ($kolam) => (int) $kolam->transaksi_berhasil > 0)
            ->sort(static function ($left, $right): int {
                $jumlahComparison = (int) $right->transaksi_berhasil <=> (int) $left->transaksi_berhasil;

                return $jumlahComparison !== 0
                    ? $jumlahComparison
                    : (float) $right->total_pendapatan <=> (float) $left->total_pendapatan;
            })
            ->values();

        $kolamTerlarisId = null;
        if ($peringkatKolam->isNotEmpty()) {
            $teratas = $peringkatKolam->first();
            $peringkatKedua = $peringkatKolam->get(1);

            if (
                $peringkatKedua === null
                || (int) $teratas->transaksi_berhasil !== (int) $peringkatKedua->transaksi_berhasil
                || (float) $teratas->total_pendapatan !== (float) $peringkatKedua->total_pendapatan
            ) {
                $kolamTerlarisId = $teratas->pool_id;
            }
        }

        return view('admin_master.transaksi', compact(
            'transaksi',
            'kolam',
            'statistik',
            'pendapatanKolam',
            'kolamTerlarisId'
        ));
    }
}
