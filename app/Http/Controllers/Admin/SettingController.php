<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $groups = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::find($key);

            if (! $setting) {
                continue;
            }

            $stored = $setting->is_encrypted ? encrypt((string) $value) : (string) $value;
            $setting->update(['value' => $stored]);
        }

        return back()->with('success', 'Settings saved.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:settings,key', 'regex:/^[a-z0-9_.]+$/'],
            'value' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'in:string,boolean,integer,json'],
            'group' => ['required', 'string', 'max:50'],
            'is_encrypted' => ['nullable', 'boolean'],
        ]);

        Setting::set(
            $validated['key'],
            $validated['value'] ?? '',
            [
                'type' => $validated['type'],
                'group' => $validated['group'],
                'is_encrypted' => (bool) ($validated['is_encrypted'] ?? false),
            ]
        );

        return back()->with('success', 'Setting added.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return back()->with('success', 'Setting deleted.');
    }
}
