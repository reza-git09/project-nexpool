<?php

namespace App\Http\Controllers;

use App\Mail\AdminApplicationApproved;
use App\Mail\AdminApplicationRejected;
use App\Models\AdminApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminApplicationController extends Controller
{
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
        $plainPassword = Str::random(8).rand(10, 99).Str::upper(Str::random(2));

        // Buat akun admin_pool
        $user = User::create([
            'full_name' => $app->full_name,
            'name' => $app->full_name,
            'username' => $app->username,
            'email' => $app->email,
            'phone' => $app->phone,
            'position' => $app->position,
            'pool_id' => $app->pool_id,
            'password' => Hash::make($plainPassword),
            'role' => 'admin_pool',
            'must_change_password' => true,
        ]);

        // Update status pengajuan
        $app->update([
            'status' => AdminApplication::STATUS_APPROVED,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => session('admin_id'),
        ]);

        // Kirim email persetujuan
        try {
            Mail::to($app->email)->send(new AdminApplicationApproved($app, $plainPassword));
        } catch (\Throwable $e) {
            // Gagal kirim email tidak membatalkan proses approve
            logger()->error('Gagal kirim email approve: '.$e->getMessage());
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
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $app->update([
            'status' => AdminApplication::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => session('admin_id'),
        ]);

        // Kirim email penolakan
        try {
            Mail::to($app->email)->send(new AdminApplicationRejected($app));
        } catch (\Throwable $e) {
            logger()->error('Gagal kirim email reject: '.$e->getMessage());
        }

        return back()->with('success', "Pengajuan {$app->full_name} telah ditolak dan email pemberitahuan dikirim.");
    }
}
