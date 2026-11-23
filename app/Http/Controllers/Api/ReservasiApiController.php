<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HargaTiket;
use App\Models\Promo;
use App\Models\RescheduleRequest;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservasiApiController extends Controller
{
    private const ADMIN_FEE = 2000;

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
                    'max:255',
                ],

                'no_hp' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'tanggal_kunjungan' => [
                    'required',
                    'date',
                ],

                'jumlah_dewasa' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'jumlah_anak' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'promo_id' => [
                    'nullable',
                    'integer',
                ],

                'total_harga' => [
                    'required',
                    'numeric',
                    'min:0',
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

            $tanggalKunjungan = Carbon::parse($validated['tanggal_kunjungan']);
            $jenisHari = $tanggalKunjungan->isWeekend() ? 'Weekend' : 'Weekday';
            $hargaTiket = HargaTiket::query()
                ->where('pool_id', $validated['pool_id'])
                ->where('jenis_hari', $jenisHari)
                ->value('harga');

            if ($hargaTiket === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harga tiket untuk kolam dan tanggal tersebut belum tersedia.',
                ], 422);
            }

            $hargaDewasa = (int) round((float) $hargaTiket);
            $subtotalTiket = $validated['jumlah_dewasa'] * $hargaDewasa;
            $subtotal = $subtotalTiket + self::ADMIN_FEE;
            $promo = null;
            $potonganPromo = 0;

            if (! empty($validated['promo_id'])) {
                $promo = Promo::query()
                    ->whereKey($validated['promo_id'])
                    ->where('pool_id', $validated['pool_id'])
                    ->where('status', true)
                    ->whereDate('tanggal_mulai', '<=', $tanggalKunjungan)
                    ->whereDate('tanggal_selesai', '>=', $tanggalKunjungan)
                    ->first();

                if ($promo === null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Promo tidak aktif atau tidak berlaku untuk kolam dan tanggal kunjungan ini.',
                    ], 422);
                }

                $nilaiDiskon = (float) $promo->nilai_diskon;
                $potonganPromo = $promo->jenis_diskon === 'Persentase'
                    ? (int) round($subtotalTiket * $nilaiDiskon / 100)
                    : (int) round($nilaiDiskon);
                $potonganPromo = max(0, min($subtotalTiket, $potonganPromo));
            }

            $totalHarga = $subtotal - $potonganPromo;

            if ((int) round((float) $validated['total_harga']) !== $totalHarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran berubah. Periksa kembali harga tiket dan promo.',
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
                    'RSV-'.
                    now()->format('Ymd').
                    '-'.
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
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
                'jumlah_dewasa' => $validated['jumlah_dewasa'],
                'jumlah_anak' => $validated['jumlah_anak'],
                'total_harga' => $totalHarga,
                'nama_promo' => $promo?->nama_promo,
                'potongan_promo' => $potonganPromo,
                'status_reservasi' => 'Menunggu',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat',
                'data' => [
                    'id' => $reservasi->id,
                    'kode_reservasi' => $reservasi->kode_reservasi,
                    'pool_id' => $reservasi->pool_id,
                    'nama_pengunjung' => $reservasi->nama_pengunjung,
                    'no_hp' => $reservasi->no_hp,
                    'tanggal_kunjungan' => $reservasi->tanggal_kunjungan,
                    'jumlah_dewasa' => $reservasi->jumlah_dewasa,
                    'jumlah_anak' => $reservasi->jumlah_anak,
                    'nama_promo' => $reservasi->nama_promo,
                    'potongan_promo' => $reservasi->potongan_promo,
                    'subtotal_harga' => $subtotal,
                    'total_harga' => $reservasi->total_harga,
                    'status_reservasi' => $reservasi->status_reservasi,
                    'created_at' => $reservasi->created_at,
                    'updated_at' => $reservasi->updated_at,
                ],
            ], 201);

        } catch (ValidationException $e) {

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

            if (! $reservasi) {
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

            if (! $reservasi) {
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

    public function quoteReschedule(Request $request, string $kode): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'max:20'],
            'tanggal_kunjungan' => ['required', 'date_format:Y-m-d'],
        ]);

        $reservasi = Reservasi::query()
            ->where('kode_reservasi', $kode)
            ->firstOrFail();
        $this->assertMatchingPhone($reservasi, $validated['no_hp']);
        $this->assertRescheduleEligible(
            $reservasi,
            Carbon::createFromFormat('Y-m-d', $validated['tanggal_kunjungan']),
        );
        $this->expireActiveRescheduleRequests($reservasi);

        if ($this->hasActiveRescheduleRequest($reservasi)) {
            abort(422, 'Masih ada pengajuan reschedule aktif untuk tiket ini.');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tanggal_lama' => $reservasi->tanggal_kunjungan?->format('Y-m-d'),
                'tanggal_baru' => $validated['tanggal_kunjungan'],
                'selisih_harga' => $this->rescheduleDifference(
                    $reservasi,
                    Carbon::createFromFormat('Y-m-d', $validated['tanggal_kunjungan']),
                ),
            ],
        ]);
    }

    public function requestReschedule(Request $request, string $kode): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'max:20'],
            'tanggal_kunjungan' => ['required', 'date_format:Y-m-d'],
        ], [
            'no_hp.required' => 'Nomor HP pemesan wajib diisi.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan baru wajib dipilih.',
            'tanggal_kunjungan.date_format' => 'Format tanggal kunjungan tidak valid.',
        ]);

        $rescheduleRequest = DB::transaction(function () use ($kode, $validated): RescheduleRequest {
            $reservasi = Reservasi::query()
                ->where('kode_reservasi', $kode)
                ->lockForUpdate()
                ->firstOrFail();
            $newDate = Carbon::createFromFormat(
                'Y-m-d',
                $validated['tanggal_kunjungan'],
            );

            $this->assertMatchingPhone($reservasi, $validated['no_hp']);
            $this->assertRescheduleEligible($reservasi, $newDate);
            $this->expireActiveRescheduleRequests($reservasi);

            if ($this->hasActiveRescheduleRequest($reservasi)) {
                abort(422, 'Masih ada pengajuan reschedule aktif untuk tiket ini.');
            }

            $difference = $this->rescheduleDifference($reservasi, $newDate);

            return RescheduleRequest::query()->create([
                'reservasi_id' => $reservasi->id,
                'tanggal_lama' => $reservasi->tanggal_kunjungan,
                'tanggal_baru' => $newDate->toDateString(),
                'selisih_harga' => $difference,
                'status' => RescheduleRequest::STATUS_AWAITING_APPROVAL,
                'payment_status' => $difference === 0 ? 'not_required' : null,
                'expires_at' => now()->addHours(24),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan reschedule menunggu persetujuan admin.',
            'data' => $this->rescheduleRequestData($rescheduleRequest),
        ], 201);
    }

    public function showReschedule(Request $request, string $kode): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'max:20'],
        ]);

        $data = DB::transaction(function () use ($kode, $validated): array {
            $reservasi = Reservasi::query()
                ->where('kode_reservasi', $kode)
                ->lockForUpdate()
                ->firstOrFail();
            $this->assertMatchingPhone($reservasi, $validated['no_hp']);
            $this->expireActiveRescheduleRequests($reservasi);

            $latestRequest = RescheduleRequest::query()
                ->where('reservasi_id', $reservasi->id)
                ->latest('id')
                ->first();
            $latestSuccessfulRequest = RescheduleRequest::query()
                ->where('reservasi_id', $reservasi->id)
                ->where('status', RescheduleRequest::STATUS_COMPLETED)
                ->latest('id')
                ->first();
            $firstSuccessfulRequest = RescheduleRequest::query()
                ->where('reservasi_id', $reservasi->id)
                ->where('status', RescheduleRequest::STATUS_COMPLETED)
                ->oldest('id')
                ->first();

            return [
                'status_reservasi' => $reservasi->status_reservasi,
                'tanggal_kunjungan' => $reservasi->tanggal_kunjungan?->format('Y-m-d'),
                'tanggal_kunjungan_awal' => $firstSuccessfulRequest
                    ?->tanggal_lama
                    ?->format('Y-m-d')
                    ?? $reservasi->tanggal_kunjungan?->format('Y-m-d'),
                'request' => $latestRequest
                    ? $this->rescheduleRequestData($latestRequest)
                    : null,
                'latest_successful_request' => $latestSuccessfulRequest
                    ? $this->rescheduleRequestData($latestSuccessfulRequest)
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    private function assertMatchingPhone(Reservasi $reservasi, string $phone): void
    {
        $savedPhone = preg_replace('/\D+/', '', (string) $reservasi->no_hp);
        $providedPhone = preg_replace('/\D+/', '', $phone);

        if ($savedPhone === '' || ! hash_equals($savedPhone, $providedPhone)) {
            abort(403, 'Data pemesan tidak cocok dengan reservasi.');
        }
    }

    private function assertRescheduleEligible(Reservasi $reservasi, Carbon $newDate): void
    {
        if ($reservasi->status_pembayaran !== 'paid') {
            abort(422, 'Reservasi hanya dapat dijadwalkan ulang setelah pembayaran lunas.');
        }

        if (in_array($reservasi->status_reservasi, ['Dibatalkan', 'Selesai'], true)) {
            abort(422, 'Tiket yang dibatalkan atau sudah digunakan tidak dapat dijadwalkan ulang.');
        }

        if (
            $reservasi->tanggal_kunjungan !== null
            && $reservasi->tanggal_kunjungan->lt(today())
        ) {
            abort(422, 'Reschedule tidak dapat dilakukan setelah tanggal kunjungan.');
        }

        $minimumDate = today()->addDays(2);
        if ($reservasi->tanggal_kunjungan !== null) {
            $minimumTicketDate = $reservasi->tanggal_kunjungan->copy()->addDays(2);
            if ($minimumTicketDate->greaterThan($minimumDate)) {
                $minimumDate = $minimumTicketDate;
            }
        }

        if (
            $reservasi->tanggal_kunjungan === null
            || $newDate->toDateString() < $minimumDate->toDateString()
        ) {
            abort(
                422,
                'Tanggal baru reschedule minimal H+2 dari hari ini atau tanggal kunjungan saat ini, mana yang lebih akhir.',
            );
        }

        if ($reservasi->tanggal_kunjungan->isSameDay($newDate)) {
            abort(422, 'Tanggal kunjungan baru harus berbeda dari tanggal saat ini.');
        }
    }

    private function rescheduleDifference(Reservasi $reservasi, Carbon $newDate): int
    {
        $oldDayType = $reservasi->tanggal_kunjungan?->isWeekend()
            ? 'Weekend'
            : 'Weekday';
        $newDayType = $newDate->isWeekend() ? 'Weekend' : 'Weekday';

        $oldPrice = HargaTiket::query()
            ->where('pool_id', $reservasi->pool_id)
            ->where('jenis_hari', $oldDayType)
            ->value('harga');
        $newPrice = HargaTiket::query()
            ->where('pool_id', $reservasi->pool_id)
            ->where('jenis_hari', $newDayType)
            ->value('harga');

        if ($oldPrice === null || $newPrice === null) {
            abort(422, 'Harga tiket untuk tanggal reschedule belum tersedia.');
        }

        $priceDifferencePerAdult = (int) round((float) $newPrice)
            - (int) round((float) $oldPrice);

        return max(0, $priceDifferencePerAdult) * $reservasi->jumlah_dewasa;
    }

    private function expireActiveRescheduleRequests(Reservasi $reservasi): void
    {
        RescheduleRequest::query()
            ->where('reservasi_id', $reservasi->id)
            ->whereIn('status', RescheduleRequest::ACTIVE_STATUSES)
            ->where('expires_at', '<=', now())
            ->update(['status' => RescheduleRequest::STATUS_EXPIRED]);
    }

    private function hasActiveRescheduleRequest(Reservasi $reservasi): bool
    {
        return RescheduleRequest::query()
            ->where('reservasi_id', $reservasi->id)
            ->whereIn('status', RescheduleRequest::ACTIVE_STATUSES)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * @return array<string, mixed>
     */
    private function rescheduleRequestData(RescheduleRequest $rescheduleRequest): array
    {
        return [
            'id' => $rescheduleRequest->id,
            'tanggal_lama' => $rescheduleRequest->tanggal_lama?->format('Y-m-d'),
            'tanggal_baru' => $rescheduleRequest->tanggal_baru?->format('Y-m-d'),
            'selisih_harga' => $rescheduleRequest->selisih_harga,
            'status' => $rescheduleRequest->status,
            'alasan_penolakan' => $rescheduleRequest->alasan_penolakan,
            'payment_status' => $rescheduleRequest->payment_status,
            'payment_order_id' => $rescheduleRequest->payment_order_id,
            'payment_expires_at' => $rescheduleRequest->payment_expires_at?->toIso8601String(),
            'expires_at' => $rescheduleRequest->expires_at?->toIso8601String(),
            'paid_at' => $rescheduleRequest->paid_at?->toIso8601String(),
        ];
    }
}
