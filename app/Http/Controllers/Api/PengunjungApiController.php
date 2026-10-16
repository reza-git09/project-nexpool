<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PengunjungApiController extends Controller
{
    /**
     * REGISTRASI PENGUNJUNG
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pengunjung,email',
            'username' => 'required|string|max:50|unique:pengunjung,username',
            'password' => 'required|string|min:6',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        $pengunjung = Pengunjung::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data' => [
                'id' => $pengunjung->id,
                'nama' => $pengunjung->nama,
                'email' => $pengunjung->email,
                'username' => $pengunjung->username,
                'no_hp' => $pengunjung->no_hp,
                'alamat' => $pengunjung->alamat,
            ],
        ], 201);
    }

    /**
     * LOGIN PENGUNJUNG
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($validated['login']);

        $pengunjung = Pengunjung::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (
            !$pengunjung ||
            !Hash::check($validated['password'], $pengunjung->password)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Email/username atau password salah.',
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
}