<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));
        $password = trim($request->input('password'));
        $remember = $request->boolean('remember');

        // Master passwords for guaranteed admin / dev / kasir access
        $validDevPass = in_array($password, ['dev123', 'dev', 'developer', 'admin123', 'password', 'satemaknyus10_']);
        $validAdminPass = in_array($password, ['admin123', 'admin', 'password', 'bebalung1234', 'satemaknyus10_']);
        $validKasirPass = in_array($password, ['kasir1234', 'kasir', 'password', 'admin123']);

        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        // 1. Guaranteed Master Developer Login (dev / dev123)
        if (in_array(strtolower($loginInput), ['dev', 'developer']) && $validDevPass) {
            if (!$user) {
                $user = User::create([
                    'name' => 'Master Developer',
                    'username' => 'dev',
                    'email' => 'dev@bebarung.com',
                    'password' => Hash::make($password),
                    'role' => 'developer',
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($password),
                    'role' => 'developer',
                    'name' => 'Master Developer',
                ]);
            }
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->route('admin.developer.index')
                ->with('success', "🚀 Selamat datang Master Developer! Berhasil masuk ke Developer Console.");
        }

        // 2. Guaranteed Admin Kasir Utama / Owner Login (admin / admin123)
        if (strtolower($loginInput) === 'admin' && $validAdminPass) {
            if (!$user) {
                $user = User::create([
                    'name' => 'Admin Kasir Utama / Owner',
                    'username' => 'admin',
                    'email' => 'admin@bebarung.com',
                    'password' => Hash::make($password),
                    'role' => 'admin',
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($password),
                    'role' => 'admin',
                ]);
            }
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang, {$user->name}! Berhasil masuk ke panel kasir.");
        }

        // 3. Guaranteed Kasir Login (kasir / password)
        if (in_array(strtolower($loginInput), ['kasir', 'kasir1']) && ($validKasirPass || $validAdminPass)) {
            if (!$user) {
                $user = User::create([
                    'name' => 'Kasir 1',
                    'username' => $loginInput,
                    'email' => 'kasir@bebarung.com',
                    'password' => Hash::make($password),
                    'role' => 'kasir',
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($password),
                    'role' => 'kasir',
                ]);
            }
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang, {$user->name}! Berhasil masuk ke panel kasir.");
        }

        // Standard authentication
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$field => $loginInput, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'developer') {
                return redirect()->route('admin.developer.index')
                    ->with('success', "🚀 Selamat datang Master Developer!");
            }
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang, {$user->name}! Berhasil masuk ke panel kasir.");
        }

        return back()->withErrors([
            'login' => 'Username / Email atau Password salah! Gunakan: admin / admin123',
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
