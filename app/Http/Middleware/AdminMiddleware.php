<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Cek apakah user memiliki role admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Halaman ini hanya untuk admin.');
        }
        
        return $next($request);
    }
}