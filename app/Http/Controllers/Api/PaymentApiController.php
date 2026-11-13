<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentApiController extends Controller
{
    public function createPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'total_price' => ['required', 'integer', 'min:1'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
        ]);

        $this->configureMidtrans();

        try {
            $orderId = 'NEXPOOL-'.now()->format('YmdHis');
            $transaction = Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $validated['total_price'],
                ],
                'customer_details' => [
                    'first_name' => $validated['customer_name'] ?? 'Pelanggan NexPool',
                    'email' => $validated['customer_email'] ?? 'user@nexpool.test',
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Midtrans Snap payment creation failed.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi pembayaran.',
            ], 502);
        }
    }

    public function createQris(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_code' => ['required', 'string', 'exists:reservasis,kode_reservasi'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
        ]);

        $reservation = Reservasi::where(
            'kode_reservasi',
            $validated['reservation_code'],
        )->firstOrFail();

        $this->configureMidtrans();

        if ($reservation->status_pembayaran === 'paid') {
            return response()->json([
                'status' => 'success',
                'order_id' => $reservation->midtrans_order_id,
                'status_pembayaran' => 'paid',
                'qr_url' => $reservation->midtrans_qr_url,
                'paid_at' => $reservation->paid_at,
            ]);
        }

        // Jika sudah ada transaksi QRIS pending sebelumnya, verifikasi apakah masih aktif di Midtrans
        if (
            $reservation->status_pembayaran === 'pending'
            && $reservation->midtrans_order_id
            && $reservation->midtrans_qr_url
        ) {
            try {
                $statusResp = Transaction::status($reservation->midtrans_order_id);
                $statusData = json_decode(
                    json_encode($statusResp, JSON_THROW_ON_ERROR),
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );
                $txStatus = strtolower((string) ($statusData['transaction_status'] ?? ''));

                if ($txStatus === 'settlement' || $txStatus === 'capture') {
                    $this->syncPaymentStatus($reservation, $statusData);
                    $reservation->refresh();

                    return response()->json([
                        'status' => 'success',
                        'order_id' => $reservation->midtrans_order_id,
                        'status_pembayaran' => 'paid',
                        'qr_url' => $reservation->midtrans_qr_url,
                        'paid_at' => $reservation->paid_at,
                    ]);
                }

                if ($txStatus === 'pending') {
                    $txTime = isset($statusData['transaction_time'])
                        ? Carbon::parse($statusData['transaction_time'], 'Asia/Jakarta')
                        : Carbon::parse($reservation->updated_at, 'Asia/Jakarta');
                    $txExpiry = isset($statusData['expiry_time'])
                        ? Carbon::parse($statusData['expiry_time'], 'Asia/Jakarta')
                        : $txTime->copy()->addMinutes(5);

                    $secondsLeft = Carbon::now('Asia/Jakarta')->diffInSeconds($txExpiry, false);

                    if ($secondsLeft > 10) {
                        return response()->json([
                            'status' => 'success',
                            'order_id' => $reservation->midtrans_order_id,
                            'status_pembayaran' => 'pending',
                            'qr_url' => $reservation->midtrans_qr_url,
                            'server_time' => Carbon::now('Asia/Jakarta')->toIso8601String(),
                            'created_at' => $txTime->toIso8601String(),
                            'expired_at' => $txExpiry->toIso8601String(),
                            'remaining_seconds' => (int) $secondsLeft,
                        ]);
                    }

                    Log::info("Transaksi Midtrans {$reservation->midtrans_order_id} sisa waktunya habis ({$secondsLeft}s). Membuat transaksi baru.");
                } else {
                    Log::info("Transaksi Midtrans lama {$reservation->midtrans_order_id} berstatus {$txStatus}. Membuat QRIS baru.");
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal periksa status transaksi lama {$reservation->midtrans_order_id}: {$e->getMessage()}. Membuat QRIS baru.");
            }
        }

        try {
            $orderId = $reservation->kode_reservasi.'-'.strtoupper(Str::random(6));
            $nowWib = Carbon::now('Asia/Jakarta');
            $expiredAtWib = $nowWib->copy()->addMinutes(5);

            $transaction = CoreApi::charge([
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) round((float) $reservation->total_harga),
                ],
                'customer_details' => [
                    'first_name' => $validated['customer_name']
                        ?? $reservation->nama_pengunjung,
                    'email' => $validated['customer_email'] ?? 'user@nexpool.test',
                    'phone' => $reservation->no_hp,
                ],
                'qris' => [
                    'acquirer' => 'gopay',
                ],
                'custom_expiry' => [
                    'order_time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
                    'expiry_duration' => 5,
                    'unit' => 'minute',
                ],
            ]);

            $transactionData = json_decode(
                json_encode($transaction, JSON_THROW_ON_ERROR),
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
            $qrAction = collect($transactionData['actions'] ?? [])
                ->firstWhere('name', 'generate-qr-code');
            $qrUrl = is_array($qrAction) ? ($qrAction['url'] ?? null) : null;

            if (
                ($transactionData['status_code'] ?? null) !== '201'
                || ! is_string($qrUrl)
                || $qrUrl === ''
            ) {
                throw new \RuntimeException('Midtrans tidak mengembalikan URL QRIS.');
            }

            $reservation->forceFill([
                'midtrans_order_id' => $orderId,
                'midtrans_transaction_id' => $transactionData['transaction_id'] ?? null,
                'midtrans_qr_url' => $qrUrl,
                'payment_method' => 'qris',
                'status_pembayaran' => 'pending',
            ])->save();

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'status_pembayaran' => 'pending',
                'qr_url' => $qrUrl,
                'server_time' => $nowWib->toIso8601String(),
                'created_at' => $nowWib->toIso8601String(),
                'expired_at' => $expiredAtWib->toIso8601String(),
                'remaining_seconds' => 300,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Midtrans QRIS creation failed.', [
                'reservation_code' => $reservation->kode_reservasi,
                'message' => $exception->getMessage(),
            ]);

            $message = str_contains($exception->getMessage(), 'HTTP status code: 401')
                ? 'Midtrans menolak Server Key (HTTP 401). Periksa bahwa MIDTRANS_SERVER_KEY adalah Server Key Sandbox saat MIDTRANS_IS_PRODUCTION=false, lalu bersihkan config dan restart server Laravel.'
                : (config('app.debug') ? 'Gagal membuat QRIS: ' . $exception->getMessage() : 'Gagal membuat QRIS. Silakan coba lagi.');

            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 502);
        }
    }

    public function status(string $orderId): JsonResponse
    {
        $reservation = Reservasi::where('midtrans_order_id', $orderId)->firstOrFail();

        if ($reservation->status_pembayaran !== 'paid') {
            $this->configureMidtrans();

            try {
                $transaction = Transaction::status($orderId);
                $transactionData = json_decode(
                    json_encode($transaction, JSON_THROW_ON_ERROR),
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );

                $this->syncPaymentStatus($reservation, $transactionData);
                $reservation->refresh();
            } catch (\Throwable $exception) {
                Log::warning('Midtrans status check failed.', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Status pembayaran belum dapat diperiksa.',
                ], 502);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $reservation->midtrans_order_id,
                'reservation_code' => $reservation->kode_reservasi,
                'status_pembayaran' => $reservation->status_pembayaran,
                'paid_at' => $reservation->paid_at,
                'qr_url' => $reservation->midtrans_qr_url,
                'server_time' => Carbon::now('Asia/Jakarta')->toIso8601String(),
            ],
        ]);
    }

    public function qrImage(string $orderId)
    {
        $reservation = Reservasi::where('midtrans_order_id', $orderId)->firstOrFail();
        abort_unless(
            filled($reservation->midtrans_transaction_id),
            404,
            'QRIS belum tersedia.',
        );

        $this->assertMidtransConfigured();

        $url = Config::getBaseUrl()
            .'/v2/qris/'
            .rawurlencode($reservation->midtrans_transaction_id)
            .'/qr-code';

        try {
            $caPath = storage_path('cacert.pem');
            $httpClient = Http::withBasicAuth(
                (string) config('services.midtrans.server_key'),
                '',
            )
                ->accept('image/png')
                ->timeout(15);

            if (file_exists($caPath)) {
                $httpClient = $httpClient->withOptions(['verify' => $caPath]);
            }

            if (! config('services.midtrans.is_production') || app()->isLocal()) {
                $httpClient = $httpClient->withoutVerifying();
            }

            $response = $httpClient->get($url);

            if (! $response->successful()) {
                Log::warning('Midtrans QR image request failed.', [
                    'order_id' => $orderId,
                    'http_status' => $response->status(),
                ]);

                return response()->json([
                    'message' => 'Gambar QRIS belum dapat dimuat. Coba muat ulang.',
                ], 502);
            }

            $contentType = strtolower(
                trim(explode(';', $response->header('Content-Type', ''))[0]),
            );
            if ($contentType !== 'image/png') {
                Log::warning('Midtrans returned an unsupported QR image type.', [
                    'order_id' => $orderId,
                    'content_type' => $contentType,
                ]);

                return response()->json([
                    'message' => 'Midtrans tidak mengembalikan gambar QRIS.',
                ], 502);
            }

            $image = $response->body();
            $pngSignature = "\x89PNG\r\n\x1a\n";
            if (! str_starts_with($image, $pngSignature)) {
                $signatureOffset = strpos($image, $pngSignature);
                if (
                    $signatureOffset === false
                    || trim(substr($image, 0, $signatureOffset)) !== ''
                ) {
                    Log::warning('Midtrans returned an invalid QR PNG image.', [
                        'order_id' => $orderId,
                    ]);

                    return response()->json([
                        'message' => 'Format gambar QRIS dari Midtrans tidak valid.',
                    ], 502);
                }

                $image = substr($image, $signatureOffset);
            }

            return response()->json([
                'success' => true,
                'image' => base64_encode($image),
            ])->header('Cache-Control', 'no-store, private');
        } catch (\Throwable $exception) {
            Log::warning('Midtrans QR image request failed.', [
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gambar QRIS belum dapat dimuat. Coba muat ulang.',
            ], 502);
        }
    }

    public function notification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'numeric'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
        ]);

        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash(
            'sha512',
            $validated['order_id']
                .$validated['status_code']
                .$validated['gross_amount']
                .$serverKey,
        );

        if (! $serverKey || ! hash_equals($expectedSignature, $validated['signature_key'])) {
            return response()->json(['message' => 'Notifikasi Midtrans tidak valid.'], 403);
        }

        $reservation = Reservasi::where(
            'midtrans_order_id',
            $validated['order_id'],
        )->first();

        if (! $reservation) {
            return response()->json(['message' => 'Reservasi tidak ditemukan.'], 404);
        }

        if (
            number_format((float) $reservation->total_harga, 2, '.', '')
            !== number_format((float) $validated['gross_amount'], 2, '.', '')
        ) {
            return response()->json(['message' => 'Nominal pembayaran tidak sesuai.'], 422);
        }

        $this->syncPaymentStatus($reservation, $request->all());

        return response()->json(['success' => true]);
    }

    private function configureMidtrans(): void
{
    $this->assertMidtransConfigured();

    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = (bool) config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // Wajib ada: library Midtrans membaca key ini saat curlOptions tidak kosong.
    Config::$curlOptions = [
        CURLOPT_HTTPHEADER => [],
    ];

    $caPath = storage_path('cacert.pem');
    if (file_exists($caPath)) {
        Config::$curlOptions[CURLOPT_CAINFO] = $caPath;
    }

    if (! Config::$isProduction || app()->isLocal()) {
        Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
        Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
    }
}
    private function assertMidtransConfigured(): void
    {
        abort_unless(
            filled(config('services.midtrans.server_key')),
            503,
            'Konfigurasi Midtrans belum tersedia.',
        );
    }

    private function syncPaymentStatus(Reservasi $reservation, array $transaction): void
    {
        if (
            isset($transaction['order_id'])
            && $transaction['order_id'] !== $reservation->midtrans_order_id
        ) {
            abort(422, 'Order ID Midtrans tidak sesuai.');
        }

        if (
            isset($transaction['gross_amount'])
            && number_format((float) $reservation->total_harga, 2, '.', '')
                !== number_format((float) $transaction['gross_amount'], 2, '.', '')
        ) {
            abort(422, 'Nominal transaksi Midtrans tidak sesuai.');
        }

        $transactionStatus = strtolower(
            (string) ($transaction['transaction_status'] ?? 'pending'),
        );
        $fraudStatus = strtolower((string) ($transaction['fraud_status'] ?? 'accept'));

        $paymentStatus = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'pending',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'deny' => 'failed',
            'refund', 'partial_refund', 'chargeback' => 'refunded',
            default => 'pending',
        };

        DB::transaction(function () use ($reservation, $paymentStatus, $transaction): void {
            $lockedReservation = Reservasi::whereKey($reservation->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedReservation->status_pembayaran === 'paid'
                && ! in_array($paymentStatus, ['paid', 'refunded'], true)
            ) {
                return;
            }

            $attributes = [
                'status_pembayaran' => $paymentStatus,
                'midtrans_transaction_id' => $transaction['transaction_id']
                    ?? $lockedReservation->midtrans_transaction_id,
            ];

            if ($paymentStatus === 'paid') {
                $attributes['paid_at'] = $lockedReservation->paid_at ?? now();
            }

            $lockedReservation->forceFill($attributes)->save();
        });
    }
}
