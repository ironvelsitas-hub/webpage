<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login owner
     */
    public function showLoginForm()
    {
        // Jika sudah login sebagai owner, redirect ke dashboard
        if (Auth::check() && Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        
        return view('owner.auth.login');
    }
    
    /**
     * Proses login owner
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Cek kredensial
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek apakah role adalah owner
            if ($user->role === 'owner') {
                $request->session()->regenerate();
                return redirect()->intended(route('owner.dashboard'))->with('success', 'Selamat datang, ' . $user->name . '!');
            }
            
            // Jika bukan owner, logout dan beri pesan error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini tidak memiliki akses sebagai pemilik.',
            ])->onlyInput('email');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    
    /**
     * Logout owner
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('owner.login')->with('success', 'Anda telah logout dari Owner Panel.');
    }
}