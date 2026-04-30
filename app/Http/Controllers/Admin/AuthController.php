<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login admin
     */
    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }
    
    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            // Cek apakah user adalah admin
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
            
            // Jika bukan admin, logout dan beri pesan error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini bukan admin.',
            ])->onlyInput('email');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    
    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}