<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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
    public function show(string|int $id): JsonResponse
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
    public function showByKode(string|int $kode): JsonResponse
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

    /**
     * Mengubah tanggal kunjungan reservasi lunas dari aplikasi mobile.
     */
    public function rescheduleByKode(Request $request, string $kode): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'max:20'],
            'tanggal_kunjungan' => ['required', 'date_format:Y-m-d', 'after:today'],
        ], [
            'no_hp.required' => 'Nomor HP pemesan wajib diisi.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan baru wajib dipilih.',
            'tanggal_kunjungan.date_format' => 'Format tanggal kunjungan tidak valid.',
            'tanggal_kunjungan.after' => 'Tanggal kunjungan baru harus setelah hari ini.',
        ]);

        $reservasi = DB::transaction(function () use ($kode, $validated): ?Reservasi {
            $reservasi = Reservasi::where('kode_reservasi', $kode)
                ->lockForUpdate()
                ->first();

            if ($reservasi === null) {
                return null;
            }

            $savedPhone = preg_replace('/\D+/', '', (string) $reservasi->no_hp);
            $providedPhone = preg_replace('/\D+/', '', $validated['no_hp']);

            if ($savedPhone === '' || ! hash_equals($savedPhone, $providedPhone)) {
                abort(403, 'Data pemesan tidak cocok dengan reservasi.');
            }

            if ($reservasi->status_pembayaran !== 'paid') {
                abort(422, 'Reservasi hanya dapat dijadwalkan ulang setelah pembayaran lunas.');
            }

            if (
                $reservasi->status_reservasi === 'Dibatalkan'
                || $reservasi->status_reservasi === 'Selesai'
            ) {
                abort(422, 'Reservasi yang dibatalkan atau selesai tidak dapat dijadwalkan ulang.');
            }

            if (
                $reservasi->tanggal_kunjungan === null
                || ! $reservasi->tanggal_kunjungan->isAfter(today())
            ) {
                abort(422, 'Reschedule hanya dapat dilakukan sebelum tanggal kunjungan.');
            }

            $reservasi->tanggal_kunjungan = $validated['tanggal_kunjungan'];
            $reservasi->save();

            return $reservasi->refresh();
        });

        if ($reservasi === null) {
            return response()->json([
                'success' => false,
                'message' => 'Kode reservasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanggal kunjungan berhasil dijadwalkan ulang.',
            'data' => [
                'kode_reservasi' => $reservasi->kode_reservasi,
                'tanggal_kunjungan' => $reservasi->tanggal_kunjungan?->format('Y-m-d'),
                'status_reservasi' => $reservasi->status_reservasi,
                'status_pembayaran' => $reservasi->status_pembayaran,
            ],
        ]);
    }
}
