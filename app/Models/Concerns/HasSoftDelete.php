<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;

trait HasSoftDelete
{
    use SoftDeletes;

    public function restore(): bool
    {
        return parent::restore();
    }

    public function forceDeletePermanently(): bool
    {
        return $this->forceDelete();
    }
}
