<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->two_factor_enabled) {
            return redirect()->route('profile.2fa.show')
                ->with('warning', 'Please enable two-factor authentication to access this page.');
        }

        return $next($request);
    }
}
