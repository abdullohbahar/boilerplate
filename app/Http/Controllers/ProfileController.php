<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        $activities = $user->activities()->latest()->limit(10)->get();

        return view('profile.show', compact('user', 'activities'));
    }

    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar');
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Profile updated.']);
        }

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Password changed.']);
        }

        return back()->with('success', 'Password changed.');
    }
}
