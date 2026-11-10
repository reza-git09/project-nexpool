<?php

namespace App\Http\Controllers;

use App\Mail\AdminApplicationApproved;
use App\Mail\AdminApplicationRejected;
use App\Models\AdminApplication;
use App\Models\KolamRenang;
use App\Models\Pool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminApplicationController extends Controller
{
    // ----------------------------------------------------------------
    // Mapping pool_id → kode akses (server-side, tidak percaya client)
    // ----------------------------------------------------------------
    private const POOL_ACCESS_CODES = [
        'tiara_park'        => 'NXP-T01P',
        'kebon_agung'       => 'NXP-K02A',
        'annasya_waterpark' => 'NXP-A03W',
        'dira_park'         => 'NXP-D04P',
        'jati_park'         => 'NXP-J05P',
    ];

    // ----------------------------------------------------------------
    // GET /register  → tampilkan form pengajuan
    // ----------------------------------------------------------------
    public function showForm()
    {
        $pools = Pool::orderBy('name')->get();
        return view('auth.register', compact('pools'));
    }

    // ----------------------------------------------------------------
    // POST /register → simpan pengajuan
    // ----------------------------------------------------------------
    public function store(Request $request)
    {
        $request->merge([
            'username' => trim($request->username),
            'email'    => strtolower(trim($request->email)),
            'phone'    => trim($request->phone),
        ]);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'username'  => [
                'required', 'string', 'min:4', 'max:20',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'email'     => ['required', 'email', 'max:100'],
            'phone'     => ['required', 'string', 'regex:/^(08|62)[0-9]{8,12}$/', 'max:14'],
            'position'  => ['required', Rule::in(['kasir_tiket', 'pengelola_fasilitas', 'staf_admin'])],
            'pool_id'   => ['required', 'string', 'exists:pools,pool_id'],
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.min'       => 'Username minimal 4 karakter.',
            'username.max'       => 'Username maksimal 20 karakter.',
            'username.regex'     => 'Username hanya boleh berisi huruf, angka, dan underscore (_).',
            'email.required'     => 'Email aktif wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'phone.required'     => 'Nomor HP/WhatsApp wajib diisi.',
            'phone.regex'        => 'Nomor HP harus format Indonesia (08xx atau 62xx) dan 10–14 digit.',
            'position.required'  => 'Jabatan wajib dipilih.',
            'position.in'        => 'Jabatan tidak valid.',
            'pool_id.required'   => 'Silakan pilih wisata kolam renang.',
            'pool_id.exists'     => 'Wisata kolam renang yang dipilih tidak valid.',
        ]);

        // Verifikasi kode akses di backend
        $expectedCode = self::POOL_ACCESS_CODES[$validated['pool_id']] ?? null;
        $submittedCode = trim($request->access_code ?? '');

        if (!$expectedCode || $submittedCode !== $expectedCode) {
            return back()
                ->withErrors(['access_code' => 'Kode akses tidak sesuai dengan wisata yang dipilih.'])
                ->withInput($request->except('access_code'));
        }

        // Cek duplikasi username — di akun aktif
        if (User::where('username', $validated['username'])->exists()) {
            return back()
                ->withErrors(['username' => 'Username sudah digunakan oleh akun aktif.'])
                ->withInput();
        }

        // Cek duplikasi username — di pengajuan pending
        if (AdminApplication::where('username', $validated['username'])
                ->where('status', AdminApplication::STATUS_PENDING)->exists()) {
            return back()
                ->withErrors(['username' => 'Sudah ada pengajuan aktif dengan username ini. Tunggu proses review.'])
                ->withInput();
        }

        // Cek duplikasi email — di akun aktif
        if (User::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'Email sudah terdaftar sebagai akun aktif.'])
                ->withInput();
        }

        // Cek duplikasi email — di pengajuan pending
        if (AdminApplication::where('email', $validated['email'])
                ->where('status', AdminApplication::STATUS_PENDING)->exists()) {
            return back()
                ->withErrors(['email' => 'Sudah ada pengajuan aktif dengan email ini. Tunggu proses review.'])
                ->withInput();
        }

        // Simpan pengajuan
        AdminApplication::create([
            'full_name'   => $validated['full_name'],
            'username'    => $validated['username'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'],
            'position'    => $validated['position'],
            'pool_id'     => $validated['pool_id'],
            'access_code' => $expectedCode,
            'status'      => AdminApplication::STATUS_PENDING,
        ]);

        return back()->with('success', 'Pengajuan terkirim. Admin Master akan meninjau dan menghubungi Anda melalui email/WhatsApp.');
    }

    // ----------------------------------------------------------------
    // GET /admin/master/pengajuan → daftar pengajuan (Admin Master only)
    // ----------------------------------------------------------------
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search', '');

        $query = AdminApplication::with('pool')->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();
        $pendingCount = AdminApplication::where('status', 'pending')->count();

        return view('admin.master.applications.index', compact('applications', 'pendingCount', 'status', 'search'));
    }

    // ----------------------------------------------------------------
    // POST /admin/master/pengajuan/{app}/approve
    // ----------------------------------------------------------------
    public function approve(Request $request, AdminApplication $app)
    {
        if ($app->status !== AdminApplication::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Generate password sementara yang kuat
        $plainPassword = Str::random(8) . rand(10, 99) . Str::upper(Str::random(2));

        // Buat akun admin_pool
        $user = User::create([
            'full_name'            => $app->full_name,
            'name'                 => $app->full_name,
            'username'             => $app->username,
            'email'                => $app->email,
            'phone'                => $app->phone,
            'position'             => $app->position,
            'pool_id'              => $app->pool_id,
            'password'             => Hash::make($plainPassword),
            'role'                 => 'admin_pool',
            'must_change_password' => true,
        ]);

        // Update status pengajuan
        $app->update([
            'status'      => AdminApplication::STATUS_APPROVED,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => session('admin_id'),
        ]);

        // Kirim email persetujuan
        try {
            Mail::to($app->email)->send(new AdminApplicationApproved($app, $plainPassword));
        } catch (\Throwable $e) {
            // Gagal kirim email tidak membatalkan proses approve
            logger()->error('Gagal kirim email approve: ' . $e->getMessage());
        }

        return back()->with('success', "Pengajuan {$app->full_name} disetujui. Akun telah dibuat dan email dikirim.");
    }

    // ----------------------------------------------------------------
    // POST /admin/master/pengajuan/{app}/reject
    // ----------------------------------------------------------------
    public function reject(Request $request, AdminApplication $app)
    {
        if ($app->status !== AdminApplication::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $app->update([
            'status'           => AdminApplication::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at'      => Carbon::now(),
            'reviewed_by'      => session('admin_id'),
        ]);

        // Kirim email penolakan
        try {
            Mail::to($app->email)->send(new AdminApplicationRejected($app));
        } catch (\Throwable $e) {
            logger()->error('Gagal kirim email reject: ' . $e->getMessage());
        }

        return back()->with('success', "Pengajuan {$app->full_name} telah ditolak dan email pemberitahuan dikirim.");
    }
}

