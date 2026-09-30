<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ReservasiApiController extends Controller
{
    /**
     * GET SEMUA RESERVASI
     *
     * Bisa difilter berdasarkan pool_id.
     *
     * Contoh:
     * /api/reservasi
     * /api/reservasi?pool_id=pool_id_01
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Reservasi::query();

            if ($request->filled('pool_id')) {
                $query->where('pool_id', $request->pool_id);
            }

            $reservasi = $query
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data reservasi berhasil diambil',
                'data' => $reservasi,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data reservasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * POST RESERVASI
     *
     * Digunakan oleh Flutter ketika pengunjung
     * melakukan pemesanan tiket.
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([
                'pool_id' => 'required|string|max:255',

                'nama_pengunjung' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'no_hp' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'tanggal_kunjungan' => [
                    'required',
                    'date'
                ],

                'jumlah_dewasa' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'jumlah_anak' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'total_harga' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
            ]);

            /*
             * Pastikan minimal ada satu tiket.
             */
            if (
                $validated['jumlah_dewasa'] == 0 &&
                $validated['jumlah_anak'] == 0
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah tiket minimal 1.',
                ], 422);
            }

            /*
             * Membuat kode reservasi otomatis.
             *
             * Contoh:
             * RSV-20260924-A8F31C
             */
            do {
                $kodeReservasi =
                    'RSV-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(6));

            } while (
                Reservasi::where(
                    'kode_reservasi',
                    $kodeReservasi
                )->exists()
            );

            /*
             * Simpan reservasi.
             */
            $reservasi = Reservasi::create([
                'kode_reservasi' => $kodeReservasi,
                'pool_id' => $validated['pool_id'],
                'nama_pengunjung' => $validated['nama_pengunjung'],
                'no_hp' => $validated['no_hp'],
                'tanggal_kunjungan' =>
                    $validated['tanggal_kunjungan'],
                'jumlah_dewasa' =>
                    $validated['jumlah_dewasa'],
                'jumlah_anak' =>
                    $validated['jumlah_anak'],
                'total_harga' =>
                    $validated['total_harga'],
                'status_reservasi' => 'Menunggu',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat',
                'data' => [
                    'id' => $reservasi->id,
                    'kode_reservasi' =>
                        $reservasi->kode_reservasi,
                    'pool_id' =>
                        $reservasi->pool_id,
                    'nama_pengunjung' =>
                        $reservasi->nama_pengunjung,
                    'no_hp' =>
                        $reservasi->no_hp,
                    'tanggal_kunjungan' =>
                        $reservasi->tanggal_kunjungan,
                    'jumlah_dewasa' =>
                        $reservasi->jumlah_dewasa,
                    'jumlah_anak' =>
                        $reservasi->jumlah_anak,
                    'total_harga' =>
                        $reservasi->total_harga,
                    'status_reservasi' =>
                        $reservasi->status_reservasi,
                    'created_at' =>
                        $reservasi->created_at,
                    'updated_at' =>
                        $reservasi->updated_at,
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data reservasi tidak valid',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat reservasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * GET DETAIL RESERVASI
     *
     * Contoh:
     * /api/reservasi/1
     */
    public function show($id): JsonResponse
    {
        try {

            $reservasi = Reservasi::find($id);

            if (!$reservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail reservasi berhasil diambil',
                'data' => $reservasi,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail reservasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * GET RESERVASI BERDASARKAN KODE RESERVASI
     *
     * Contoh:
     * /api/reservasi/kode/RSV-20260924-ABC123
     */
    public function showByKode($kode): JsonResponse
    {
        try {

            $reservasi = Reservasi::where(
                'kode_reservasi',
                $kode
            )->first();

            if (!$reservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode reservasi tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil ditemukan',
                'data' => $reservasi,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari reservasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}