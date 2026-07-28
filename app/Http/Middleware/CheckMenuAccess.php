<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        $menu = Menu::where('route', $routeName)->first();

        // Route not in menu system — allow through
        if (! $menu) {
            return $next($request);
        }

        $userRoles = $request->user()->getRoleNames()->toArray();
        $allowed = $menu->roles()->pluck('name')->toArray();

        // No assignments yet — allow through (menus not configured)
        if (empty($allowed)) {
            return $next($request);
        }

        abort_unless(! empty(array_intersect($userRoles, $allowed)), 403);

        return $next($request);
    }
}
