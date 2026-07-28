<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General
            ['key' => 'app.name', 'value' => config('app.name'), 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'app.description', 'value' => '', 'type' => 'string', 'group' => 'general', 'is_encrypted' => false],

            // Mail / SMTP
            ['key' => 'mail.host', 'value' => '', 'type' => 'string', 'group' => 'mail', 'is_encrypted' => false],
            ['key' => 'mail.port', 'value' => '587', 'type' => 'integer', 'group' => 'mail', 'is_encrypted' => false],
            ['key' => 'mail.username', 'value' => '', 'type' => 'string', 'group' => 'mail', 'is_encrypted' => false],
            ['key' => 'mail.password', 'value' => '', 'type' => 'string', 'group' => 'mail', 'is_encrypted' => true],
            ['key' => 'mail.encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'mail', 'is_encrypted' => false],
            ['key' => 'mail.from_address', 'value' => config('mail.from.address', ''), 'type' => 'string', 'group' => 'mail', 'is_encrypted' => false],
            ['key' => 'mail.from_name', 'value' => config('mail.from.name', ''), 'type' => 'string', 'group' => 'mail', 'is_encrypted' => false],
        ];

        foreach ($defaults as $row) {
            Setting::firstOrCreate(['key' => $row['key']], $row);
        }
    }
}
