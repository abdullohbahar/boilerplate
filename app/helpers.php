<?php

use App\Models\Setting;
use Illuminate\Support\Carbon;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('format_date')) {
    function format_date(mixed $date, string $format = 'd M Y'): string
    {
        if (! $date) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }
}

if (! function_exists('format_currency')) {
    function format_currency(int|float $amount, string $currency = 'IDR'): string
    {
        return number_format($amount, 0, ',', '.').' '.$currency;
    }
}

if (! function_exists('format_bytes')) {
    function format_bytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
