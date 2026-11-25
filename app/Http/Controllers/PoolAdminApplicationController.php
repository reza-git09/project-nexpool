<?php

namespace App\Http\Controllers;

use App\Models\AdminApplication;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

        $phone = $request->input('phone');
        if (is_string($phone)) {
            $phone = preg_replace('/\D+/', '', $phone);
            while (str_starts_with($phone, '62') || str_starts_with($phone, '0')) {
                $phone = str_starts_with($phone, '62')
                    ? substr($phone, 2)
                    : substr($phone, 1);
            }
            $request->merge(['phone' => $phone]);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'bail',
                'required',
                'email',
                'max:255',
                'regex:/^[a-z0-9._%+\-]+@gmail\.com$/',
            ],
            'position' => ['bail', 'required', 'string', Rule::in(['staf_admin'])],
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^8[0-9]{8,10}$/',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:72',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (is_string($value) && strlen($value) > 72) {
                        $fail('Password terlalu panjang');
                    }
                },
            ],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi',
            'full_name.string' => 'Nama tidak valid',
            'full_name.max' => 'Nama terlalu panjang',
            'email.required' => 'Email wajib diisi',
            'email.string' => 'Gunakan email @gmail.com',
            'email.email' => 'Gunakan email @gmail.com',
            'email.max' => 'Email tidak valid',
            'email.regex' => 'Gunakan email @gmail.com',
            'position.required' => 'Jabatan wajib ditetapkan',
            'position.string' => 'Jabatan tidak valid',
            'position.in' => 'Jabatan hanya dapat berupa Staf Admin',
            'phone.required' => 'Nomor HP wajib diisi',
            'phone.string' => 'Nomor HP tidak valid',
            'phone.regex' => 'Nomor HP tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password tidak valid',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password terlalu panjang',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak sama',
        ]);

        $validator->after(function ($validator) use ($request): void {
            $email = $request->input('email');
            if (
                is_string($email)
                && preg_match('/^[a-z0-9._%+\-]+@gmail\.com$/', $email)
                && (User::query()->where('email', $email)->exists()
                    || AdminApplication::query()->where('email', $email)->exists())
            ) {
                $validator->errors()->add('email', 'Email sudah terdaftar');
            }

            $phone = $request->input('phone');
            if (is_string($phone) && preg_match('/^8[0-9]{8,10}$/', $phone)) {
                $storedPhone = '62'.$phone;
                if (
                    User::query()->where('phone', $storedPhone)->exists()
                    || AdminApplication::query()->where('phone', $storedPhone)->exists()
                    || DB::table('pengunjung')->where('no_hp', $storedPhone)->exists()
                ) {
                    $validator->errors()->add('phone', 'Nomor HP sudah digunakan');
                }
            }
        });

        $validated = $validator->validate();
        $validated['phone'] = '62'.$validated['phone'];

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
