<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * 0812xxx / +62812xxx / 812xxx  ->  62812xxx
     */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '62')) {
            return $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }
        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }

        return $digits;
    }

    public static function driver(): string
    {
        return (string) config('services.otp.driver', 'log');
    }

    /**
     * Kirim pesan WhatsApp.
     *
     * @return string|null  null = sukses, string = pesan error
     */
    public function send(string $phone, string $message): ?string
    {
        // Mode tes: tidak kirim WA, kode cukup dicatat di storage/logs/laravel.log
        if (self::driver() === 'log') {
            Log::info("[OTP-LOG] WhatsApp ke {$phone}: {$message}");
            return null;
        }

        $token = config('services.fonnte.token');
        if (!$token) {
            return 'Token layanan WhatsApp belum diatur (FONNTE_TOKEN di .env).';
        }

        try {
            $res = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->timeout(15)
                ->post('https://api.fonnte.com/send', [
                    'target'  => $phone,
                'message' => $message,
            ]);

            $json = $res->json();

            if ($res->successful() && ($json['status'] ?? false) === true) {
                return null;
            }

            Log::warning('Kirim WhatsApp gagal: ' . $res->body());

            return $json['reason'] ?? 'Gagal mengirim pesan WhatsApp.';
        } catch (\Throwable $e) {
            Log::error('Kirim WhatsApp error: ' . $e->getMessage());

            return 'Tidak bisa menghubungi layanan WhatsApp. Coba lagi nanti.';
        }
    }
}
