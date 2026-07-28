<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, config('app.supported_locales', ['en', 'id']))) {
            abort(404);
        }

        session(['locale' => $locale]);

        return back();
    }
}
