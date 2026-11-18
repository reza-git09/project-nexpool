<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\KolamRenang;
use App\Models\User;
use App\Services\AdminLoginOtpService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /** Kunci session untuk user yang sudah benar password tapi belum verifikasi OTP pertama. */
    private const OTP_SESSION_KEY = 'admin_otp_user_id';

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, AdminLoginOtpService $otp): RedirectResponse
    {
        $email = strtolower(trim($request->input('email', '')));
        $request->merge(['email' => $email]);

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $email)->first();

        // Periksa pengajuan admin jika akun belum ditemukan.
        if (! $user) {
            $pendingApp = AdminApplication::where('email', $email)
                ->where('status', AdminApplication::STATUS_PENDING)
                ->first();

            if ($pendingApp) {
                return back()
                    ->with('error', 'Akun Anda masih menunggu persetujuan Admin Master.')
                    ->withInput($request->except('password'));
            }

            $rejectedApp = AdminApplication::where('email', $email)
                ->where('status', AdminApplication::STATUS_REJECTED)
                ->first();

            if ($rejectedApp) {
                return back()
                    ->with('error', 'Pengajuan Anda telah ditolak.')
                    ->withInput($request->except('password'));
            }
        }

        // Verifikasi password.
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role === 'admin_pool' && $user->first_login_otp_verified === false) {
                return $this->startOtpChallenge($request, $user, $otp);
            }

            return $this->completeLogin($request, $user);
        }

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput($request->except('password'));
    }

    public function showOtpForm(Request $request, AdminLoginOtpService $otp): View|RedirectResponse
    {
        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.otp', [
            'maskedEmail' => $this->maskEmail((string) $user->email),
            'ttlMinutes' => AdminLoginOtpService::TTL_MINUTES,
            'resendIn' => $otp->secondsUntilResend($user),
        ]);
    }

    public function verifyOtp(Request $request, AdminLoginOtpService $otp): RedirectResponse
    {
        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi berakhir. Silakan login ulang.');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.digits' => 'Kode OTP harus 6 angka.',
        ]);

        $result = $otp->verify($user, (string) $request->input('code'));

        switch ($result['status']) {
            case 'ok':
                $user->update(['first_login_otp_verified' => true]);
                $request->session()->forget(self::OTP_SESSION_KEY);

                return $this->completeLogin($request, $user);

            case 'invalid':
                return back()->with(
                    'error',
                    'Kode OTP salah. Sisa percobaan: '.$result['remaining'].'.'
                );

            case 'locked':
                $request->session()->forget(self::OTP_SESSION_KEY);

                return redirect()->route('login')
                    ->with('error', 'Terlalu banyak kode salah. Silakan login ulang.');

            default: // expired
                $request->session()->forget(self::OTP_SESSION_KEY);

                return redirect()->route('login')
                    ->with('error', 'Kode OTP kedaluwarsa. Silakan login ulang.');
        }
    }

    public function resendOtp(Request $request, AdminLoginOtpService $otp): RedirectResponse
    {
        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi berakhir. Silakan login ulang.');
        }

        $wait = $otp->secondsUntilResend($user);
        if ($wait > 0) {
            return back()->with('error', 'Tunggu '.$wait.' detik sebelum mengirim ulang kode.');
        }

        try {
            $otp->issue($user);
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'Kode OTP gagal dikirim. Coba lagi sebentar.');
        }

        return back()->with('status', 'Kode OTP baru sudah dikirim ke email Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /** Finalisasi autentikasi dan arahkan user ke dashboard berdasarkan role. */
    private function completeLogin(Request $request, User $user): RedirectResponse
    {
        $request->session()->forget(self::OTP_SESSION_KEY);

        Auth::login($user);
        $request->session()->regenerate();

        $kolam = $user->pool_id
            ? KolamRenang::where('pool_id', $user->pool_id)->first()
            : null;

        session([
            'admin_id' => $user->id,
            'admin_username' => $user->username,
            'admin_pool_id' => $user->pool_id,
            'admin_nama' => $user->full_name
                ?? $user->name
                ?? $user->username,
            'admin_pool_nama' => $kolam?->nama_kolam
                ?? ($user->isAdminMaster() ? 'NEXPOOL PUSAT' : ($user->pool?->name ?? $user->username)),
            'admin_role' => $user->role ?? 'admin_pool',
            'admin_must_change_password' => (bool) $user->must_change_password,
        ]);

        if ($user->must_change_password) {
            return redirect()->route('admin.change-password');
        }

        // Admin Master masuk dashboard pusat.
        if ($user->isAdminMaster()) {
            return redirect('/admin-master/dashboard');
        }

        // Admin Kolam tetap masuk dashboard masing-masing.
        return redirect()->intended('/dashboard');
    }

    private function startOtpChallenge(Request $request, User $user, AdminLoginOtpService $otp): RedirectResponse
    {
        try {
            $otp->issue($user);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->with('error', 'Kode OTP gagal dikirim. Coba lagi sebentar.')
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();
        $request->session()->put(self::OTP_SESSION_KEY, $user->getKey());

        return redirect()->route('login.otp');
    }

    /** User Admin Kolam yang belum menyelesaikan verifikasi OTP pertama. */
    private function pendingOtpUser(Request $request): ?User
    {
        $id = $request->session()->get(self::OTP_SESSION_KEY);

        if (! $id) {
            return null;
        }

        $user = User::find($id);

        if (! $user || $user->role !== 'admin_pool' || $user->first_login_otp_verified !== false) {
            $request->session()->forget(self::OTP_SESSION_KEY);

            return null;
        }

        return $user;
    }

    /** adminmaster20@gmail.com -> ad*********@gmail.com */
    private function maskEmail(string $email): string
    {
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');

        return mb_substr($name, 0, 2).str_repeat('*', max(mb_strlen($name) - 2, 1)).'@'.$domain;
    }
}
