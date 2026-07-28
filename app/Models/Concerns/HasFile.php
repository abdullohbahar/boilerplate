<?php

namespace App\Models\Concerns;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

trait HasFile
{
    public static function bootHasFile(): void
    {
        static::saving(function (self $model) {
            foreach ($model->getFileFields() as $field => $options) {
                $value = $model->getAttribute($field);

                if (! ($value instanceof UploadedFile)) {
                    continue;
                }

                $model->setAttribute($field, null);

                $disk = $options['disk'] ?? 'public';
                $dir = $options['dir'] ?? static::class;
                $path = $value->store($dir, $disk);

                if (isset($options['width']) || isset($options['height'])) {
                    $img = Image::read(Storage::disk($disk)->path($path));
                    $img->scaleDown($options['width'] ?? null, $options['height'] ?? null);
                    $img->save();
                }

                $model->pendingFiles[$field] = [
                    'disk' => $disk,
                    'path' => $path,
                    'original_name' => $value->getClientOriginalName(),
                    'mime_type' => $value->getMimeType(),
                    'size' => $value->getSize(),
                ];
            }
        });

        static::saved(function (self $model) {
            foreach ($model->pendingFiles ?? [] as $field => $data) {
                // Delete old file for single-file fields
                $model->files()->where('field', $field)->each(fn (File $f) => $f->delete());

                $model->files()->create([...$data, 'field' => $field]);
            }

            $model->pendingFiles = [];
        });

        static::deleted(function (self $model) {
            $model->files()->each(fn (File $f) => $f->delete());
        });
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function file(string $field): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('field', $field)->orderBy('sort_order');
    }

    public function fileUrl(string $field, string $default = ''): string
    {
        return $this->file($field)->first()?->url() ?? $default;
    }

    protected function getFileFields(): array
    {
        return $this->fileFields ?? [];
    }
}
