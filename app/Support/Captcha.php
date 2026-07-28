<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Captcha
{
    public static function enabled(): bool
    {
        return (bool) config('captcha.enabled');
    }

    public static function verify(Request $request): bool
    {
        if (! self::enabled()) {
            return true;
        }

        $provider = config('captcha.provider');

        $token = match ($provider) {
            'cloudflare' => $request->input('cf-turnstile-response'),
            default => $request->input('g-recaptcha-response'),
        };

        if (! $token) {
            return false;
        }

        $endpoint = match ($provider) {
            'cloudflare' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            default => 'https://www.google.com/recaptcha/api/siteverify',
        };

        $secret = match ($provider) {
            'cloudflare' => config('captcha.cloudflare.secret'),
            default => config('captcha.google.secret'),
        };

        $response = Http::asForm()->post($endpoint, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        return (bool) ($response->json('success') ?? false);
    }
}
