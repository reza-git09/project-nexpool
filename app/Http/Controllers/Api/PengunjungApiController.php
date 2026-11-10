<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PengunjungApiController extends Controller
{
    /**
     * REGISTRASI PENGUNJUNG
     */
    public function register(Request $request): JsonResponse
    {
        $request->merge([
            'no_hp' => WhatsAppService::normalizePhone(
                (string) $request->input('no_hp', '')
            ),
        ]);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:pengunjung,email'],
            'password' => ['required', 'string', 'min:6'],
            'no_hp' => ['required', 'string', 'min:9', 'max:20', 'unique:pengunjung,no_hp'],
        ]);

        $pengunjung = Pengunjung::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data' => [
                'id' => $pengunjung->id,
                'nama' => $pengunjung->nama,
                'email' => $pengunjung->email,
                'no_hp' => $pengunjung->no_hp,
            ],
        ], 201);
    }

    /**
     * LOGIN PENGUNJUNG
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($validated['login']);
        $phone = WhatsAppService::normalizePhone($login);

        $matches = Pengunjung::query()
            ->where('email', $login)
            ->orWhere('no_hp', $login)
            ->orWhere('no_hp', $phone)
            ->get();

        $pengunjung = $matches->first(
            fn (Pengunjung $candidate): bool => Hash::check(
                $validated['password'],
                $candidate->password
            )
        );

        if (! $pengunjung) {
            return response()->json([
                'success' => false,
                'message' => 'Email/nomor HP atau password salah.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'id' => $pengunjung->id,
                'nama' => $pengunjung->nama,
                'email' => $pengunjung->email,
                'username' => $pengunjung->username,
                'no_hp' => $pengunjung->no_hp,
                'alamat' => $pengunjung->alamat,
            ],
        ], 200);
    }

    /**
     * CEK KEBERADAAN AKUN (untuk alur lupa password)
     * POST /api/check-account
     */
    public function checkAccount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'min:9', 'max:20'],
        ]);

        $phone = trim($validated['no_hp']);
        $normalizedPhone = WhatsAppService::normalizePhone($phone);

        if (! Pengunjung::query()
            ->where('no_hp', $phone)
            ->orWhere('no_hp', $normalizedPhone)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dengan nomor HP tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akun ditemukan.',
        ], 200);
    }

    /**
     * RESET PASSWORD PENGUNJUNG
     * POST /api/reset-password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'min:9', 'max:20'],
            'otp_token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $phone = trim($validated['no_hp']);
        $normalizedPhone = WhatsAppService::normalizePhone($phone);
        $tokenHash = hash('sha256', $validated['otp_token']);

        $updated = DB::transaction(function () use (
            $phone,
            $normalizedPhone,
            $tokenHash,
            $validated
        ): bool {
            $otp = DB::table('otp_codes')
                ->where('phone', $normalizedPhone)
                ->where('token_hash', $tokenHash)
                ->whereNotNull('verified_at')
                ->whereNull('used_at')
                ->where('verified_at', '>=', now()->subMinutes(15))
                ->lockForUpdate()
                ->first();

            $pengunjung = Pengunjung::query()
                ->where('no_hp', $phone)
                ->orWhere('no_hp', $normalizedPhone)
                ->lockForUpdate()
                ->first();

            if (! $otp || ! $pengunjung) {
                return false;
            }

            $pengunjung->password = Hash::make($validated['password']);
            $pengunjung->save();

            DB::table('otp_codes')
                ->where('id', $otp->id)
                ->update([
                    'used_at' => now(),
                    'updated_at' => now(),
                ]);

            return true;
        });

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi nomor HP tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ], 200);
    }
}
