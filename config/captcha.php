<?php

return [

    'enabled' => env('CAPTCHA_ENABLED', false),

    'provider' => env('CAPTCHA_PROVIDER', 'google'), // google | cloudflare

    'google' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'cloudflare' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret' => env('TURNSTILE_SECRET_KEY'),
    ],

];
