<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KaryawanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isKaryawan()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk karyawan.');
        }

        return $next($request);
    }
}