<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentApiController extends Controller
{
    public function createPayment(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [],
        ];

        $orderId = 'NEXPOOL-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name ?? 'Pelanggan NexPool',
                'email' => $request->customer_email ?? 'user@nexpool.test',
            ],
            // 'enabled_payments' => ['qris', 'gopay', 'bank_transfer'],
        ];

        try {
            $transaction = Snap::createTransaction($params);

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
