<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckTeacherRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        // 2. Ambil role dari user yang sedang login
        $userRole = Auth::user()->role;

        // 3. Jika rolenya BUKAN 'teacher' dan BUKAN 'admin', usir!
        if ($userRole !== 'teacher' && $userRole !== 'admin') {
            abort(403, 'Akses Ditolak! Halaman ini hanya untuk Guru dan Admin.');
        }

        // Jika lolos dari semua cegatan di atas, persilakan masuk
        return $next($request);
    }
}