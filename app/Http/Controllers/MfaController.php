<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQRCode;
use App\Support\SecurityAudit;

class MfaController extends Controller
{
    public function setup(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['super_admin', 'admin', 'hr_admin'], true), 403);
        $secret = $request->session()->get('mfa_setup_secret') ?? app(Google2FA::class)->generateSecretKey();
        $request->session()->put('mfa_setup_secret', $secret);

        return view('auth.mfa-setup', [
            'secret' => $secret,
            'qrCodeUrl' => app(Google2FA::class)->getQRCodeUrl(
                config('app.name'),
                $request->user()->email,
                $secret
            ),
            'qrCode' => app(Google2FAQRCode::class)->getQRCodeInline(
                config('app.name'),
                $request->user()->email,
                $secret
            ),
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $secret = $request->session()->get('mfa_setup_secret');
        abort_unless($secret, 422, 'Sesi setup MFA sudah berakhir.');

        if (!app(Google2FA::class)->verifyKey($secret, $request->string('code'))) {
            return back()->withErrors(['code' => 'Kode MFA tidak valid.']);
        }

        $request->user()->forceFill([
            'mfa_secret' => $secret,
            'mfa_enabled_at' => now(),
        ])->save();
        $request->session()->forget('mfa_setup_secret');
        SecurityAudit::record('mfa_enabled', $request);

        return redirect()->route('dashboard.admin')->with('success', 'MFA berhasil diaktifkan.');
    }

    public function challengeForm()
    {
        abort_unless(session()->has('mfa_user_id'), 403);
        return view('auth.mfa-challenge');
    }

    public function challenge(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $key = 'mfa:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Terlalu banyak percobaan MFA.');
        }

        $user = \App\Models\User::find($request->session()->get('mfa_user_id'));
        if (!$user || !$user->requiresMfa() || !app(Google2FA::class)->verifyKey($user->mfa_secret, $request->string('code'))) {
            RateLimiter::hit($key, 60);
            return back()->withErrors(['code' => 'Kode MFA tidak valid.']);
        }

        Auth::login($user, $request->session()->pull('mfa_remember', false));
        $request->session()->forget('mfa_user_id');
        RateLimiter::clear($key);
        $request->session()->regenerate();
        SecurityAudit::record('mfa_login_success', $request);

        return redirect()->intended('/');
    }
}
