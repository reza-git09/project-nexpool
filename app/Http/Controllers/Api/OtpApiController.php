<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtpApiController extends Controller
{
    private const EXPIRES_SECONDS = 300; // kode berlaku 5 menit
    private const RESEND_SECONDS  = 60;  // jeda kirim ulang
    private const MAX_ATTEMPTS    = 5;   // salah input maksimal
    private const MAX_PER_HOUR    = 5;   // kirim OTP maksimal per nomor per jam

    private function hashCode(string $phone, string $code): string
    {
        return hash_hmac('sha256', $phone . '|' . $code, (string) config('app.key'));
    }

    /**
     * POST /api/otp/send   { no_hp }
     */
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'no_hp' => ['required', 'string', 'min:9', 'max:20'],
        ]);

        $phone = WhatsAppService::normalizePhone($data['no_hp']);

        if (!preg_match('/^628\d{7,12}$/', $phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak valid. Gunakan nomor WhatsApp Indonesia, contoh 08123456789.',
            ], 422);
        }

        // Jeda kirim ulang
        $last = DB::table('otp_codes')->where('phone', $phone)->orderByDesc('id')->first();
        if ($last) {
            $boleh = strtotime((string) $last->created_at) + self::RESEND_SECONDS;
            if ($boleh > time()) {
                $sisa = $boleh - time();
                return response()->json([
                    'success'     => false,
                    'message'     => "Tunggu {$sisa} detik sebelum meminta kode lagi.",
                    'retry_after' => $sisa,
                ], 429);
            }
        }

        // Batas kirim per jam
        $jumlah = DB::table('otp_codes')
            ->where('phone', $phone)
            ->where('created_at', '>=', now()->subHour())
            ->count();
        if ($jumlah >= self::MAX_PER_HOUR) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu sering meminta kode. Coba lagi dalam 1 jam.',
            ], 429);
        }

        $code = (string) random_int(100000, 999999);

        $id = DB::table('otp_codes')->insertGetId([
            'phone'      => $phone,
            'code_hash'  => $this->hashCode($phone, $code),
            'attempts'   => 0,
            'expires_at' => now()->addSeconds(self::EXPIRES_SECONDS),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $menit = intdiv(self::EXPIRES_SECONDS, 60);
        $error = (new WhatsAppService())->send(
            $phone,
            "*NexPool*\nKode verifikasi kamu: *{$code}*\nBerlaku {$menit} menit. Jangan bagikan kode ini ke siapa pun."
        );

        if ($error !== null) {
            DB::table('otp_codes')->where('id', $id)->delete();

            return response()->json([
                'success' => false,
                'message' => $error,
            ], 502);
        }

        $response = [
            'success'    => true,
            'message'    => 'Kode OTP sudah dikirim ke WhatsApp.',
            'expires_in' => self::EXPIRES_SECONDS,
            'resend_in'  => self::RESEND_SECONDS,
        ];

        // Hanya untuk tes lokal (OTP_DRIVER=log dan APP_DEBUG=true)
        if (WhatsAppService::driver() === 'log' && config('app.debug')) {
            $response['debug_code'] = $code;
        }

        return response()->json($response, 200);
    }

    /**
     * POST /api/otp/verify   { no_hp, code }
     * Sukses -> mengembalikan otp_token untuk dikirim ke /api/register
     */
    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'no_hp' => ['required', 'string', 'min:9', 'max:20'],
            'code'  => ['required', 'digits:6'],
        ]);

        $phone = WhatsAppService::normalizePhone($data['no_hp']);

        $row = DB::table('otp_codes')
            ->where('phone', $phone)
            ->whereNull('verified_at')
            ->orderByDesc('id')
            ->first();

        if (!$row || strtotime((string) $row->expires_at) < time()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP sudah kedaluwarsa. Minta kode baru.',
            ], 422);
        }

        if ($row->attempts >= self::MAX_ATTEMPTS) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan salah. Minta kode baru.',
            ], 429);
        }

        DB::table('otp_codes')->where('id', $row->id)->increment('attempts');

        if (!hash_equals($row->code_hash, $this->hashCode($phone, $data['code']))) {
            $sisa = max(0, self::MAX_ATTEMPTS - ($row->attempts + 1));

            return response()->json([
                'success' => false,
                'message' => $sisa > 0
                    ? "Kode OTP salah. Sisa percobaan: {$sisa}."
                    : 'Kode OTP salah. Percobaan habis, minta kode baru.',
            ], 422);
        }

        $token = Str::random(48);

        DB::table('otp_codes')->where('id', $row->id)->update([
            'verified_at' => now(),
            'token_hash'  => hash('sha256', $token),
            'updated_at'  => now(),
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Nomor HP berhasil diverifikasi.',
            'otp_token' => $token,
        ], 200);
    }
}