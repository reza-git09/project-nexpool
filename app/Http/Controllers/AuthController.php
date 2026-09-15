<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\KolamRenang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'pool_id' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)
            ->where('pool_id', $request->pool_id)
            ->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            $kolam = KolamRenang::where('pool_id', $admin->pool_id)->first();

            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_pool_id' => $admin->pool_id,
                'admin_nama' => $admin->nama_admin,
                'admin_pool_nama' => $kolam?->nama_kolam ?? $admin->nama_admin,
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Username, Pool ID, atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/login');
    }
}