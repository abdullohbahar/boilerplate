<?php

namespace App\Policies;

use App\Models\User;

/**
 * Example policy for User resource.
 *
 * Usage in controllers:
 *   $this->authorize('update', $user);
 *   Gate::allows('delete', $user);
 *
 * Usage in Blade:
 *
 *   @can('update', $user) ... @endcan
 *
 * Policy auto-discovery is enabled in Laravel 13 — no manual registration needed.
 */
class UserPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    public function view(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    public function delete(User $authUser, User $user): bool
    {
        // Prevent self-deletion and admin-on-admin deletion
        return $authUser->hasRole('admin') && $authUser->id !== $user->id;
    }
}
