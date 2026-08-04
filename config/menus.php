<?php

/*
 * Menu definitions. Each entry corresponds to a sidebar item.
 * Run `php artisan menu:sync` after changing this file.
 */

return [
    [
        'key' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => 'dashboard',
        'route' => 'dashboard',
        'parent_key' => null,
        'sort' => 10,
    ],
    [
        'key' => 'profile',
        'label' => 'Profile',
        'icon' => 'profile',
        'route' => 'profile',
        'parent_key' => null,
        'sort' => 20,
    ],
    [
        'key' => 'admin.users',
        'label' => 'Users',
        'icon' => 'users',
        'route' => 'admin.users.index',
        'parent_key' => null,
        'sort' => 100,
    ],
    [
        'key' => 'admin.roles',
        'label' => 'Roles',
        'icon' => 'roles',
        'route' => 'admin.roles.index',
        'parent_key' => null,
        'sort' => 110,
    ],
    [
        'key' => 'admin.activity',
        'label' => 'Activity Log',
        'icon' => 'activity',
        'route' => 'admin.activity.index',
        'parent_key' => null,
        'sort' => 120,
    ],
    [
        'key' => 'admin.menus',
        'label' => 'Menu Access',
        'icon' => 'menus',
        'route' => 'admin.menus.index',
        'parent_key' => null,
        'sort' => 130,
    ],
    [
        'key' => 'admin.logs',
        'label' => 'Log Viewer',
        'icon' => 'logs',
        'route' => 'admin.logs.index',
        'parent_key' => null,
        'sort' => 145,
    ],
    [
        'key' => 'admin.settings',
        'label' => 'Settings',
        'icon' => 'settings',
        'route' => 'admin.settings.index',
        'parent_key' => null,
        'sort' => 140,
    ],
];
