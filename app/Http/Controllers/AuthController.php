<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\User;
use App\Models\Pool;
use App\Models\KolamRenang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $pools = Pool::orderBy('pool_id')->get();

        return view('auth.login', compact('pools'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pool_id'  => 'nullable|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $poolId = trim($request->pool_id ?? '');

        // Cari akun berdasarkan username.
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // Admin Kolam wajib memilih pool yang sesuai.
        // Admin Master tidak membutuhkan pool_id.
        if ($user && $user->role !== 'admin_master') {
            if ($poolId === '' || $user->pool_id != $poolId) {
                $user = null;
            }
        }

        // Periksa pengajuan admin jika akun belum ditemukan.
        if (!$user && $poolId !== '') {
            $pendingApp = AdminApplication::where('username', $username)
                ->where('pool_id', $poolId)
                ->where('status', AdminApplication::STATUS_PENDING)
                ->first();

            if ($pendingApp) {
                return back()
                    ->with('error', 'Akun Anda masih menunggu persetujuan Admin Master.')
                    ->withInput($request->except('password'));
            }

            $rejectedApp = AdminApplication::where('username', $username)
                ->where('pool_id', $poolId)
                ->where('status', AdminApplication::STATUS_REJECTED)
                ->first();

            if ($rejectedApp) {
                $reason = $rejectedApp->rejection_reason
                    ? ' Alasan: ' . $rejectedApp->rejection_reason
                    : '';

                return back()
                    ->with('error', 'Pengajuan Anda telah ditolak.' . $reason)
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
            ->with('error', 'Username, Pool ID, atau password salah.')
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
