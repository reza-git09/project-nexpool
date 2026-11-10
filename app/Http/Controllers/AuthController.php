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
            'pool_id'  => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $poolId   = trim($request->pool_id);

        $user = User::where('username', $username)
            ->where('pool_id', $poolId)
            ->first();

        // Cek apakah ada pengajuan yang masih pending (username + pool_id match)
        if (!$user) {
            $pendingApp = AdminApplication::where('username', $username)
                ->where('pool_id', $poolId)
                ->where('status', AdminApplication::STATUS_PENDING)
                ->first();

            if ($pendingApp) {
                return back()
                    ->with('error', 'Akun Anda masih menunggu persetujuan Admin Master. Kami akan menghubungi Anda via email/WhatsApp.')
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
                    ->with('error', 'Pengajuan Anda telah ditolak oleh Admin Master.' . $reason)
                    ->withInput($request->except('password'));
            }
        }

        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);
            $request->session()->regenerate();

            $kolam = KolamRenang::where('pool_id', $user->pool_id)->first();

            session([
                'admin_id'                  => $user->id,
                'admin_username'            => $user->username,
                'admin_pool_id'             => $user->pool_id,
                'admin_nama'                => $user->full_name ?? $user->name ?? $user->username,
                'admin_pool_nama'           => $kolam?->nama_kolam ?? ($user->pool?->name ?? $user->username),
                'admin_role'                => $user->role ?? 'admin_pool',
                'admin_must_change_password'=> (bool)($user->must_change_password ?? false),
            ]);

            // Redirect ke halaman ganti password jika wajib
            if ($user->must_change_password) {
                return redirect()->route('admin.change-password');
            }

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
