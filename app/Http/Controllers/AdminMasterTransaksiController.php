<?php

namespace App\Http\Controllers;

use App\Models\KolamRenang;
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

        // Pencairan tidak mengurangi pendapatan kotor dari transaksi yang sudah dibayar.
        $pendapatanKolam = Reservasi::with('kolamRenang')
            ->where('status_pembayaran', 'paid')
            ->selectRaw('pool_id, COUNT(*) as jumlah_transaksi, SUM(total_harga) as total_pendapatan')
            ->groupBy('pool_id')
            ->get();

        return view('admin_master.transaksi', compact(
            'transaksi',
            'kolam',
            'statistik',
            'pendapatanKolam'
        ));
    }
}
