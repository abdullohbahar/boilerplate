<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMailSettingsFromDb();
    }

    private function loadMailSettingsFromDb(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        try {
            $host = setting('mail.host');

            if ($host) {
                config([
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => setting('mail.port', 587),
                    'mail.mailers.smtp.username' => setting('mail.username'),
                    'mail.mailers.smtp.password' => setting('mail.password'),
                    'mail.mailers.smtp.encryption' => setting('mail.encryption', 'tls'),
                    'mail.from.address' => setting('mail.from_address', config('mail.from.address')),
                    'mail.from.name' => setting('mail.from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Throwable) {
            // Silently fail during migrations / fresh installs
        }
    }
}
