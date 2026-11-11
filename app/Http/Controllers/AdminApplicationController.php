<?php

namespace App\Http\Controllers;

use App\Mail\AdminApplicationApproved;
use App\Mail\AdminApplicationRejected;
use App\Models\AdminApplication;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AdminApplicationController extends Controller
{
    // ----------------------------------------------------------------
    // GET /admin/master/pengajuan → daftar pengajuan (Admin Master only)
    // ----------------------------------------------------------------
    public function index(Request $request): View
    {
        $statusInput = $request->query('status', 'pending');
        $searchInput = $request->query('search', '');
        $status = is_string($statusInput) ? $statusInput : 'pending';
        $search = is_string($searchInput) ? trim($searchInput) : '';

        $query = AdminApplication::query()
            ->select([
                'id',
                'full_name',
                'username',
                'email',
                'phone',
                'position',
                'pool_id',
                'status',
                'rejection_reason',
                'reviewed_at',
                'created_at',
            ])
            ->with('pool')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();
        $pendingCount = AdminApplication::query()
            ->where('status', AdminApplication::STATUS_PENDING)
            ->count();

        return view('admin.master.applications.index', compact('applications', 'pendingCount', 'status', 'search'));
    }

    // ----------------------------------------------------------------
    // POST /admin/master/pengajuan/{app}/approve
    // ----------------------------------------------------------------
    public function approve(Request $request, AdminApplication $app): RedirectResponse
    {
        $result = DB::transaction(function () use ($app, $request): array {
            $application = AdminApplication::query()
                ->whereKey($app->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($application->status !== AdminApplication::STATUS_PENDING) {
                return ['processed' => true];
            }

            if (User::query()->where('email', $application->email)->exists()) {
                return ['duplicate' => true];
            }

            $plainPassword = null;
            $passwordHash = $application->password_hash;

            if (! $passwordHash) {
                $plainPassword = Str::random(16);
                $passwordHash = Hash::make($plainPassword);
            }

            User::create([
                'full_name' => $application->full_name,
                'name' => $application->full_name,
                'username' => $application->username,
                'email' => $application->email,
                'phone' => $application->phone,
                'position' => $application->position,
                'pool_id' => $application->pool_id,
                'password' => $passwordHash,
                'role' => 'admin_pool',
                'must_change_password' => $plainPassword !== null,
            ]);

            $application->update([
                'status' => AdminApplication::STATUS_APPROVED,
                'password_hash' => null,
                'reviewed_at' => now(),
                'reviewed_by' => $request->session()->get('admin_id'),
            ]);

            return [
                'application' => $application->load('pool'),
                'plain_password' => $plainPassword,
            ];
        });

        if ($result['processed'] ?? false) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($result['duplicate'] ?? false) {
            return back()->with('error', 'Email pengajuan sudah digunakan oleh akun lain. Akun tidak dibuat.');
        }

        /** @var AdminApplication $application */
        $application = $result['application'];

        try {
            Mail::to($application->email)->send(
                new AdminApplicationApproved($application, $result['plain_password'])
            );
        } catch (Throwable $exception) {
            Log::error('Gagal mengirim email persetujuan Admin NEXPOOL.', [
                'application_id' => $application->id,
                'exception' => $exception,
            ]);

            return back()->with(
                'warning',
                $result['plain_password'] === null
                    ? 'Akun berhasil dibuat, tetapi email pemberitahuan gagal dikirim. Admin baru dapat login menggunakan password yang didaftarkan.'
                    : 'Akun lama berhasil dibuat, tetapi email password sementara gagal dikirim. Password perlu diatur ulang secara aman sebelum admin dapat login.'
            );
        }

        return back()->with('success', "Pengajuan {$application->full_name} disetujui dan akun admin berhasil dibuat.");
    }

    // ----------------------------------------------------------------
    // POST /admin/master/pengajuan/{app}/reject
    // ----------------------------------------------------------------
    public function reject(Request $request, AdminApplication $app): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $rejectedApplication = DB::transaction(function () use ($app, $request, $validated): ?AdminApplication {
            $application = AdminApplication::query()
                ->whereKey($app->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($application->status !== AdminApplication::STATUS_PENDING) {
                return null;
            }

            $application->update([
                'status' => AdminApplication::STATUS_REJECTED,
                'rejection_reason' => $validated['rejection_reason'],
                'password_hash' => null,
                'reviewed_at' => now(),
                'reviewed_by' => $request->session()->get('admin_id'),
            ]);

            return $application->load('pool');
        });

        if (! $rejectedApplication) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        try {
            Mail::to($rejectedApplication->email)->send(new AdminApplicationRejected($rejectedApplication));
        } catch (Throwable $exception) {
            Log::error('Gagal mengirim email penolakan Admin NEXPOOL.', [
                'application_id' => $rejectedApplication->id,
                'exception' => $exception,
            ]);

            return back()->with(
                'warning',
                'Pengajuan berhasil ditolak, tetapi email pemberitahuan gagal dikirim.'
            );
        }

        return back()->with('success', "Pengajuan {$rejectedApplication->full_name} telah ditolak.");
    }
}
