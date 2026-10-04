<?php

namespace App\Http\Controllers;

use App\Models\Pool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $pools = Pool::orderBy('pool_id')->get();
        return view('auth.register', compact('pools'));
    }

    public function register(Request $request)
    {
        // 1. Server-side Input Validation
        $request->merge([
            'username' => trim($request->username),
        ]);

        $request->validate([
            'username' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'regex:/^[a-zA-Z0-9_.@]+$/',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('pool_id', $request->pool_id);
                }),
            ],
            'pool_id' => [
                'required',
                'string',
                'exists:pools,pool_id',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'registration_code' => [
                'required',
                'string',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 30 karakter.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, garis bawah (_), dan @.',
            'username.unique' => 'Username sudah digunakan untuk wisata kolam renang yang dipilih.',
            'pool_id.required' => 'Silakan pilih wisata kolam renang.',
            'pool_id.exists' => 'Wisata kolam renang yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'registration_code.required' => 'Kode registrasi wisata wajib diisi.',
        ]);

        // 2. Cross-check Pool & Registration Code Server-Side
        $pool = Pool::where('pool_id', $request->pool_id)->first();

        if (!$pool || !Hash::check($request->registration_code, $pool->registration_code)) {
            return back()
                ->withErrors(['registration_code' => 'Kode registrasi tidak valid.'])
                ->withInput($request->except(['password', 'password_confirmation', 'registration_code']));
        }

        // 3. Create User Account
        $username = trim($request->username);
        User::create([
            'username' => $username,
            'name'     => $username,
            'pool_id'  => $pool->pool_id,
            'email'    => $username . '@' . $pool->pool_id . '.test',
            'password' => $request->password, // Automatically hashed by model cast
        ]);

        // 4. Redirect to login with success flash message and pre-filled fields
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login dengan akun yang baru dibuat.')
            ->with('registered_username', $username)
            ->with('registered_pool_id', $pool->pool_id);
    }
}
