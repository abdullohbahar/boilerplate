<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;

class MenuSync extends Command
{
    protected $signature = 'menu:sync';

    protected $description = 'Sync menus table from config/menus.php';

    public function handle(): int
    {
        $items = config('menus', []);

        foreach ($items as $item) {
            Menu::updateOrCreate(
                ['key' => $item['key']],
                [
                    'label' => $item['label'],
                    'icon' => $item['icon'] ?? null,
                    'route' => $item['route'] ?? null,
                    'parent_key' => $item['parent_key'] ?? null,
                    'sort' => $item['sort'] ?? 0,
                ],
            );
        }

        $this->info(sprintf('Synced %d menu items.', count($items)));

        return self::SUCCESS;
    }
}
