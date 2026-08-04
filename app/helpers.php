<?php

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
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

if (! function_exists('success')) {
    function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }
}

if (! function_exists('failed')) {
    function failed(string $message, mixed $errors = null, int $status = 422): JsonResponse
    {
        $payload = ['message' => $message];
        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
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
