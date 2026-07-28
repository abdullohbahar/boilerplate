<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function (self $model) {
            if (Schema::hasColumn($model->getTable(), 'slug') && empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->{$model->slugFrom ?? 'name'});
            }
        });

        static::updating(function (self $model) {
            if (Schema::hasColumn($model->getTable(), 'slug') && $model->isDirty($model->slugFrom ?? 'name')) {
                $model->slug = $model->generateUniqueSlug($model->{$model->slugFrom ?? 'name'}, $model->getKey());
            }
        });
    }

    protected function generateUniqueSlug(string $value, mixed $exceptId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->where($this->getKeyName(), '!=', $exceptId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
