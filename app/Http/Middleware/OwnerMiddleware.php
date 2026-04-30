<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user login dan memiliki role owner
        if (Auth::check() && Auth::user()->role === 'owner') {
            return $next($request);
        }
        
        // Jika bukan owner, redirect ke halaman login owner
        return redirect()->route('owner.login')->with('error', 'Akses ditolak! Hanya pemilik yang dapat mengakses halaman ini.');
    }
}