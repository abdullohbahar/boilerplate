<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $qrCodeUrl = null;
        $secret = null;

        if (! $user->two_factor_enabled) {
            $secret = Google2FA::generateSecretKey();
            session(['2fa_setup_secret' => $secret]);

            $qrCodeUrl = Google2FA::getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret,
            );
        }

        return view('auth.two-factor.setup', compact('qrCodeUrl', 'secret'));
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $secret = session('2fa_setup_secret');

        if (! $secret || ! Google2FA::verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'The code is invalid. Please try again.']);
        }

        $backupCodes = Collection::times(8, fn () => Str::upper(Str::random(4).'-'.Str::random(4)))->all();

        auth()->user()->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_backup_codes' => $backupCodes,
            'two_factor_enabled' => true,
        ]);

        session()->forget('2fa_setup_secret');

        return redirect()->route('profile.2fa.show')->with('backup_codes', $backupCodes);
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        auth()->user()->update([
            'two_factor_secret' => null,
            'two_factor_backup_codes' => null,
            'two_factor_enabled' => false,
        ]);

        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
