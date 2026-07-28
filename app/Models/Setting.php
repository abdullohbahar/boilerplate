<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value', 'type', 'group', 'is_encrypted'])]
class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('settings', fn () => static::all()->keyBy('key'));

        $setting = $settings->get($key);

        if (! $setting) {
            return $default;
        }

        $value = $setting->is_encrypted ? decrypt($setting->value) : $setting->value;

        return match ($setting->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    public static function set(string $key, mixed $value, array $options = []): void
    {
        $isEncrypted = $options['is_encrypted'] ?? false;
        $storedValue = $isEncrypted ? encrypt((string) $value) : (string) $value;

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $options['type'] ?? 'string',
                'group' => $options['group'] ?? 'general',
                'is_encrypted' => $isEncrypted,
            ]
        );

        Cache::forget('settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }
}
