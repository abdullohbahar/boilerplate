<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $fillable = ['key', 'label', 'icon', 'route', 'parent_key', 'sort'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_role', 'menu_id', 'role_name', 'id', 'name');
    }

    /**
     * Returns menus visible to the given user based on their roles.
     */
    public static function forUser(User $user): Collection
    {
        $roleNames = $user->getRoleNames();

        return static::whereHas('roles', fn ($q) => $q->whereIn('name', $roleNames))
            ->orderBy('sort')
            ->get();
    }
}
