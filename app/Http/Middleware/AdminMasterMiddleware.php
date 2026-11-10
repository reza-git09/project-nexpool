<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMasterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Harus sudah login sebagai admin
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        // Harus punya role admin_master
        if (session('admin_role') !== 'admin_master') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin Master.');
        }

        return $next($request);
    }
}

