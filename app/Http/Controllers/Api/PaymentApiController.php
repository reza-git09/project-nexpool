<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class PaymentApiController extends Controller
{
    /**
     * Konfigurasi Midtrans Environment
     */
    private function initMidtrans()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [],
        ];
    }

    /**
     * Membuat pembayaran QRIS baru
     */
    public function createQrisPayment(Request $request)
    {
        $this->initMidtrans();

        $orderId = 'NXP-' . time();

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name ?? 'Pelanggan NexPool',
                'email' => $request->customer_email ?? 'user@nexpool.test',
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        try {
            $charge = CoreApi::charge($params);

            // Midtrans mengembalikan tautan gambar QR code di array actions
            $qrImageUrl = null;
            if (isset($charge->actions)) {
                foreach ($charge->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $qrImageUrl = $action->url;
                        break;
                    }
                }
            }

            logger("=== MIDTRANS QRIS LINK ASLI ===");
            logger($qrImageUrl);

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'qr_string' => $charge->qr_string ?? null,
                'qr_url' => $qrImageUrl,
                'gross_amount' => $charge->gross_amount,
                'transaction_status' => $charge->transaction_status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cek status transaksi real-time ke Midtrans berdasarkan Order ID
     */
    public function checkStatus(string $orderId)
    {
        $this->initMidtrans();

        try {
            $status = (object) Transaction::status($orderId);

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'transaction_status' => $status->transaction_status ?? ($status['transaction_status'] ?? null),
                'fraud_status' => $status->fraud_status ?? ($status['fraud_status'] ?? null),
                'gross_amount' => $status->gross_amount ?? ($status['gross_amount'] ?? null),
                'payment_type' => $status->payment_type ?? ($status['payment_type'] ?? null),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
