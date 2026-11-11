<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\KolamRenang;
use Illuminate\Http\Request;

class AdminMasterTransaksiApiController extends Controller
{
    /**
     * DASHBOARD STATISTIK TRANSAKSI ADMIN MASTER
     */
    public function dashboard()
    {
        $query = Reservasi::query();

        return response()->json([
            'success' => true,
            'message' => 'Statistik transaksi berhasil diambil',
            'data' => [
                'total_transaksi' => (clone $query)->count(),

                'pembayaran_berhasil' => (clone $query)
                    ->where('status_pembayaran', 'paid')
                    ->count(),

                'pembayaran_pending' => (clone $query)
                    ->where('status_pembayaran', 'pending')
                    ->count(),

                'pembayaran_gagal' => (clone $query)
                    ->whereIn('status_pembayaran', [
                        'failed',
                        'expire',
                        'expired',
                        'deny',
                        'cancel'
                    ])
                    ->count(),

                'total_pendapatan' => (clone $query)
                    ->where('status_pembayaran', 'paid')
                    ->sum('total_harga'),

                'total_kolam' => KolamRenang::count(),
            ],
        ], 200);
    }

    /**
     * DAFTAR SELURUH TRANSAKSI
     */
    public function index(Request $request)
    {
        $query = Reservasi::with('kolamRenang');

        // Filter berdasarkan kolam renang
        if ($request->filled('pool_id')) {
            $query->where('pool_id', $request->pool_id);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where(
                'status_pembayaran',
                $request->status_pembayaran
            );
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate(
                'created_at',
                $request->tanggal
            );
        }

        $transaksi = $query
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil diambil',
            'data' => $transaksi,
        ], 200);
    }

    /**
     * DETAIL TRANSAKSI
     */
    public function show($id)
    {
        $transaksi = Reservasi::with('kolamRenang')
            ->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil',
            'data' => $transaksi,
        ], 200);
    }

    /**
     * DAFTAR KOLAM UNTUK FILTER ADMIN MASTER
     */
    public function kolam()
    {
        $kolam = KolamRenang::select(
            'pool_id',
            'nama_kolam',
            'status'
        )->orderBy('nama_kolam')->get();

        return response()->json([
            'success' => true,
            'data' => $kolam,
        ], 200);
    }
}
