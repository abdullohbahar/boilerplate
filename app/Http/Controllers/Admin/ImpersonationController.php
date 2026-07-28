<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(User $user): RedirectResponse
    {
        abort_if($user->hasRole('admin'), 403, 'Cannot impersonate another admin.');

        session(['impersonator_id' => Auth::id()]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('Started impersonating user');

        Auth::loginUsingId($user->id);

        return redirect('/')->with('success', "You are now impersonating {$user->name}.");
    }

    public function stop(): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');

        abort_unless($impersonatorId, 403);

        session()->forget('impersonator_id');

        Auth::loginUsingId($impersonatorId);

        activity()
            ->causedBy(Auth::user())
            ->log('Stopped impersonating user');

        return redirect()->route('admin.users.index')->with('success', 'Returned to your account.');
    }
}
