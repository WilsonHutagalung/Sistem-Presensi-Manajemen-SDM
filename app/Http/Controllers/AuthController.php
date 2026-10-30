<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Support\SecurityAudit;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);
        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            $employee = Employee::with('user')
                ->where('employee_number', $credentials['email'])
                ->first();

            if (!$employee || !$employee->user) {
                RateLimiter::hit($throttleKey, 60);
                return back()->withErrors([
                    'email' => 'NIK atau email tidak ditemukan.',
                ])->onlyInput('email');
            }

            $credentials['email'] = $employee->user->email;
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->employee && Auth::user()->employee->status !== 'active') {
                Auth::logout();
                RateLimiter::hit($throttleKey, 60);

                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
                ])->onlyInput('email');
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            if (Auth::user()->requiresMfa()) {
                $request->session()->put([
                    'mfa_user_id' => Auth::id(),
                    'mfa_remember' => $request->boolean('remember'),
                ]);
                Auth::logout();
                return redirect()->route('mfa.challenge.form');
            }

            SecurityAudit::record('login_success', $request);
            return $this->redirectBasedOnRole(Auth::user());
        }

        RateLimiter::hit($throttleKey, 60);
        SecurityAudit::record('login_failed', $request, ['identifier' => $credentials['email']]);
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    protected function redirectBasedOnRole($user)
    {
        if (in_array($user->role, ['super_admin', 'admin', 'hr_admin', 'manager'])) {
            return redirect()->route('dashboard.admin');
        }
        return redirect()->route('dashboard.employee');
    }
}
