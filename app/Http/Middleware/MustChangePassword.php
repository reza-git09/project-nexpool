<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            session()->has('admin_id') &&
            session('admin_must_change_password') === true &&
            !$request->routeIs('admin.change-password') &&
            !$request->routeIs('admin.change-password.process') &&
            !$request->routeIs('logout')
        ) {
            return redirect()->route('admin.change-password');
        }

        return $next($request);
    }
}

