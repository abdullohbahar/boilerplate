<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Captcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Captcha::verify($request)) {
            return back()->withErrors(['captcha' => 'Please complete the captcha.'])->onlyInput('email');
        }

        if (config('auth.login_throttle')) {
            $key = 'login:'.Str::lower($request->input('email')).'|'.$request->ip();
            $maxAttempts = (int) config('auth.login_max_attempts', 5);
            $decayMinutes = (int) config('auth.login_decay_minutes', 5);

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);

                return back()->withErrors([
                    'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
                ])->onlyInput('email');
            }
        }

        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            if ($user->two_factor_enabled) {
                session(['2fa_user_id' => $user->id, '2fa_remember' => $request->boolean('remember')]);

                if (isset($key, $decayMinutes)) {
                    RateLimiter::clear($key);
                }

                return redirect()->route('two-factor.challenge');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if (isset($key, $decayMinutes)) {
                RateLimiter::clear($key);
            }

            return redirect()->intended('/');
        }

        if (isset($key, $decayMinutes)) {
            RateLimiter::hit($key, $decayMinutes * 60);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
