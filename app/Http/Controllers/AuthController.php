<?php

namespace App\Http\Controllers;

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

        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);
            $request->session()->regenerate();

            $kolam = KolamRenang::where('pool_id', $user->pool_id)->first();

            session([
                'admin_id'        => $user->id,
                'admin_username'  => $user->username,
                'admin_pool_id'   => $user->pool_id,
                'admin_nama'      => $user->name ?? $user->username,
                'admin_pool_nama' => $kolam?->nama_kolam ?? ($user->pool?->name ?? $user->username),
            ]);

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