<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Banned = 'banned';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Banned => 'Banned',
        };
    }

    /**
     * Tailwind / Meridian badge variant for this status.
     * Usage: <span class="badge badge--{{ $user->status->color() }}">{{ $user->status->label() }}</span>
     */
    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'neutral',
            self::Banned => 'danger',
        };
    }
}
