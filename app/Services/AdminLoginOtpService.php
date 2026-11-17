<?php

namespace App\Services;

use App\Mail\AdminLoginOtpMail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Kode OTP login pertama Admin Kolam (dikirim lewat email).
 *
 *  - kode 6 angka, disimpan sebagai hash (HMAC), bukan teks biasa
 *  - berlaku 5 menit, sekali pakai
 *  - salah MAX_ATTEMPTS kali -> kode dihapus, harus login ulang
 *  - kirim ulang dibatasi jeda RESEND_COOLDOWN_SECONDS
 */
class AdminLoginOtpService
{
    public const TTL_MINUTES = 5;

    public const MAX_ATTEMPTS = 5;

    public const RESEND_COOLDOWN_SECONDS = 60;

    private const TABLE = 'admin_login_otps';

    /**
     * Buat kode baru (menggantikan kode lama) lalu kirim ke email user.
     *
     * @throws Throwable bila email gagal dikirim (kode otomatis dihapus)
     */
    public function issue(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $now = now();

        DB::transaction(function () use ($user, $code, $now): void {
            DB::table(self::TABLE)->where('user_id', $user->getKey())->delete();

            DB::table(self::TABLE)->insert([
                'user_id' => $user->getKey(),
                'code_hash' => $this->hash($code),
                'attempts' => 0,
                'expires_at' => $now->copy()->addMinutes(self::TTL_MINUTES),
                'last_sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($code, self::TTL_MINUTES));
        } catch (Throwable $exception) {
            $this->clear($user);

            throw $exception;
        }
    }

    /**
     * Periksa kode yang diketik user.
     *
     * @return array{status: 'ok'|'invalid'|'expired'|'locked', remaining: int}
     */
    public function verify(User $user, string $code): array
    {
        return DB::transaction(function () use ($user, $code): array {
            $row = DB::table(self::TABLE)
                ->where('user_id', $user->getKey())
                ->lockForUpdate()
                ->first();

            if ($row === null) {
                return ['status' => 'expired', 'remaining' => 0];
            }

            if (Carbon::parse($row->expires_at)->isPast()) {
                $this->clear($user);

                return ['status' => 'expired', 'remaining' => 0];
            }

            if ((int) $row->attempts >= self::MAX_ATTEMPTS) {
                $this->clear($user);

                return ['status' => 'locked', 'remaining' => 0];
            }

            if (hash_equals($row->code_hash, $this->hash($code))) {
                $this->clear($user);

                return ['status' => 'ok', 'remaining' => self::MAX_ATTEMPTS];
            }

            $attempts = (int) $row->attempts + 1;

            if ($attempts >= self::MAX_ATTEMPTS) {
                $this->clear($user);

                return ['status' => 'locked', 'remaining' => 0];
            }

            DB::table(self::TABLE)->where('id', $row->id)->update([
                'attempts' => $attempts,
                'updated_at' => now(),
            ]);

            return ['status' => 'invalid', 'remaining' => self::MAX_ATTEMPTS - $attempts];
        });
    }

    /** Sisa detik sebelum kode boleh dikirim ulang (0 = boleh sekarang). */
    public function secondsUntilResend(User $user): int
    {
        $lastSent = DB::table(self::TABLE)->where('user_id', $user->getKey())->value('last_sent_at');

        if ($lastSent === null) {
            return 0;
        }

        $elapsed = now()->timestamp - Carbon::parse($lastSent)->timestamp;

        return max(0, self::RESEND_COOLDOWN_SECONDS - $elapsed);
    }

    public function clear(User $user): void
    {
        DB::table(self::TABLE)->where('user_id', $user->getKey())->delete();
    }

    private function hash(string $code): string
    {
        return hash_hmac('sha256', $code, (string) config('app.key'));
    }
}
