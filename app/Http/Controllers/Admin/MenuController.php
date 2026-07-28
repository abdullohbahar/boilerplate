<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::with('roles')->orderBy('sort')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.menus.index', compact('menus', 'roles'));
    }

    public function update(Request $request): RedirectResponse
    {
        $menuRoles = $request->input('menu_roles', []);

        Menu::all()->each(function (Menu $menu) use ($menuRoles) {
            $roleNames = $menuRoles[$menu->id] ?? [];
            $menu->roles()->sync($roleNames);
        });

        return back()->with('success', 'Menu access updated.');
    }
}
