<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorChallengeController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);
        $code = $request->string('code')->remove(' ')->value();

        $secret = decrypt($user->two_factor_secret);

        if (Google2FA::verifyKey($secret, $code)) {
            $request->session()->forget('2fa_user_id');
            Auth::login($user, $request->session()->pull('2fa_remember', false));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // Check backup codes
        $backupCodes = $user->two_factor_backup_codes ?? [];
        $index = array_search(strtoupper($code), $backupCodes);

        if ($index !== false) {
            unset($backupCodes[$index]);
            $user->update(['two_factor_backup_codes' => array_values($backupCodes)]);

            $request->session()->forget('2fa_user_id');
            Auth::login($user, $request->session()->pull('2fa_remember', false));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors(['code' => 'The code is invalid.']);
    }
}
