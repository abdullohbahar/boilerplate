<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        $this->shareSidebarMenus();
    }

    private function shareSidebarMenus(): void
    {
        View::composer('components.sidebar', function ($view) {
            if (! auth()->check()) {
                $view->with('sidebarMenus', collect());

                return;
            }

            try {
                if (! Schema::hasTable('menus') || Menu::count() === 0) {
                    // Menus not synced yet — fall back to config keys filtered by role
                    $user = auth()->user();
                    $isAdmin = $user->hasRole('admin');

                    $sidebarMenus = collect(config('menus', []))
                        ->when(! $isAdmin, fn ($c) => $c->reject(fn ($m) => str_starts_with($m['key'], 'admin.')))
                        ->map(fn ($m) => (object) ['key' => $m['key']]);
                } else {
                    $sidebarMenus = Menu::forUser(auth()->user());
                }
            } catch (\Throwable) {
                $sidebarMenus = collect();
            }

            $view->with('sidebarMenus', $sidebarMenus);
        });
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
