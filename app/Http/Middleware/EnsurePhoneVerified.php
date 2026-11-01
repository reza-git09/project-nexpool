<?php

namespace App\Http\Middleware;

use App\Services\WhatsAppService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $phone = WhatsAppService::normalizePhone((string) $request->input('no_hp', ''));
        $token = (string) $request->input('otp_token', '');

        $row = null;
        if ($phone !== '' && $token !== '') {
            $row = DB::table('otp_codes')
                ->where('phone', $phone)
                ->where('token_hash', hash('sha256', $token))
                ->whereNotNull('verified_at')
                ->whereNull('used_at')
                ->where('verified_at', '>=', now()->subMinutes(15))
                ->orderByDesc('id')
                ->first();
        }

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP belum diverifikasi. Verifikasi OTP WhatsApp terlebih dahulu.',
            ], 422);
        }

        $response = $next($request);

        if ($response->getStatusCode() < 300) {
            DB::table('otp_codes')->where('id', $row->id)->update([
                'used_at'    => now(),
                'updated_at' => now(),
            ]);
        }

        return $response;
    }
}