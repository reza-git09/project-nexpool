<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\KolamRenang;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
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
                $reason = $rejectedApp->rejection_reason
                    ? ' Alasan: '.$rejectedApp->rejection_reason
                    : '';

                return back()
                    ->with('error', 'Pengajuan Anda telah ditolak.'.$reason)
                    ->withInput($request->except('password'));
            }
        }

        // Verifikasi password.
        if ($user && Hash::check($request->password, $user->password)) {

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

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput($request->except('password'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
