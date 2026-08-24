<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form login panel kasir / admin.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login kasir / admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('login')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('login'));
        }

        $loginInput = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Cek login via username atau email
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang, {$user->name}! Anda berhasil masuk ke panel kasir.");
        }

        RateLimiter::hit($throttleKey, 300); // 5 menit block setelah 5x gagal

        return back()->withErrors([
            'login' => 'Username / Email atau Password salah! Akses ditolak.',
        ])->withInput($request->only('login'));
    }

    /**
     * Logout dari panel kasir / admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout dengan aman.');
    }
}
