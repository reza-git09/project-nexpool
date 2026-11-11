<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PoolAdminApplicationController extends Controller
{
    public function index(): View
    {
        $poolId = (string) session('admin_pool_id');

        $applications = AdminApplication::query()
            ->where('pool_id', $poolId)
            ->select([
                'id',
                'full_name',
                'email',
                'phone',
                'position',
                'pool_id',
                'status',
                'rejection_reason',
                'created_at',
            ])
            ->latest()
            ->paginate(15);

        return view('admin.pool.applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => Str::lower(trim($email))]);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'bail',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                Rule::unique('admin_applications', 'email'),
            ],
            'phone' => [
                'bail',
                'required',
                'string',
                'min:9',
                'max:20',
                'regex:/^[0-9+()\\s.-]+$/',
                Rule::unique('users', 'phone'),
                Rule::unique('admin_applications', 'phone'),
                Rule::unique('pengunjung', 'no_hp'),
            ],
            'position' => [
                'required',
                Rule::in(['kasir_tiket', 'pengelola_fasilitas', 'staf_admin']),
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (is_string($value) && strlen($value) > 72) {
                        $fail('Password tidak boleh melebihi 72 byte.');
                    }
                },
            ],
        ], [
            'email.unique' => 'Email sudah terdaftar atau sedang diajukan.',
            'phone.unique' => 'Nomor HP sudah digunakan.',
            'phone.regex' => 'Nomor HP hanya boleh berisi angka dan tanda +, -, spasi, atau kurung.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $poolId = (string) session('admin_pool_id');
        $usernamePrefix = Str::of(Str::before($validated['email'], '@'))
            ->lower()
            ->replaceMatches('/[^a-z0-9]/', '')
            ->substr(0, 12)
            ->toString();
        $usernamePrefix = $usernamePrefix !== '' ? $usernamePrefix : 'admin';
        $username = $this->generateUsername($usernamePrefix, $poolId);

        AdminApplication::create([
            'full_name' => $validated['full_name'],
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'position' => $validated['position'],
            'pool_id' => $poolId,
            'access_code' => Str::upper(Str::random(20)),
            'password_hash' => Hash::make($validated['password']),
            'status' => AdminApplication::STATUS_PENDING,
        ]);

        return redirect()
            ->route('admin.pool.applications.index')
            ->with('success', 'Pengajuan admin berhasil dikirim dan menunggu persetujuan Admin Master.');
    }

    private function generateUsername(string $prefix, string $poolId): string
    {
        do {
            $username = $prefix.'_'.Str::lower(Str::random(6));
        } while (
            User::query()->where('pool_id', $poolId)->where('username', $username)->exists()
            || AdminApplication::query()->where('pool_id', $poolId)->where('username', $username)->exists()
        );

        return $username;
    }
}
