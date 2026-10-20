<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan apakah dia adalah super_admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Halaman tersebut khusus untuk Super Admin.');
        }

        return $next($request);
    }
}