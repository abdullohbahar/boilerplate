<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Sync structure from config
        Artisan::call('menu:sync');

        // Default role assignments: admin sees everything, user sees non-admin menus
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if (! $adminRole) {
            return;
        }

        Menu::all()->each(function (Menu $menu) use ($adminRole, $userRole) {
            $roles = [$adminRole->name];

            if ($userRole && ! str_starts_with($menu->key, 'admin.')) {
                $roles[] = $userRole->name;
            }

            $menu->roles()->sync($roles);
        });
    }
}
