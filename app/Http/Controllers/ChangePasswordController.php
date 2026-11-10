<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('auth.change_password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.required'   => 'Password baru wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('admin_id');
        $user   = User::findOrFail($userId);

        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Update session flag
        session(['admin_must_change_password' => false]);

        return redirect('/dashboard')->with('success', 'Password berhasil diubah. Selamat datang di NEXPOOL!');
    }
}

