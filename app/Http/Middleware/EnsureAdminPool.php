<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPool
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            session('admin_role') !== 'admin_pool' ||
            ! session()->has('admin_pool_id') ||
            session('admin_pool_id') === ''
        ) {
            abort(403, 'Akses ini hanya tersedia untuk Admin Kolam Renang.');
        }

        return $next($request);
    }
}
