<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['fileable_type', 'fileable_id', 'field', 'disk', 'path', 'original_name', 'mime_type', 'size', 'sort_order'])]
class File extends Model
{
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    protected static function booted(): void
    {
        static::deleted(function (File $file) {
            Storage::disk($file->disk)->delete($file->path);
        });
    }
}
