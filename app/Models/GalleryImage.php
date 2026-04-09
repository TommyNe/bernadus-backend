<?php

namespace App\Models;

use Database\Factories\GalleryImageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    /** @use HasFactory<GalleryImageFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'alt_text',
        'sort_order',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::updated(function (GalleryImage $galleryImage): void {
            if (! $galleryImage->wasChanged('image_path')) {
                return;
            }

            $originalImagePath = $galleryImage->getOriginal('image_path');

            if (! is_string($originalImagePath) || $originalImagePath === '' || $originalImagePath === $galleryImage->image_path) {
                return;
            }

            Storage::disk('public')->delete($originalImagePath);
        });

        static::deleted(function (GalleryImage $galleryImage): void {
            if (! is_string($galleryImage->image_path) || $galleryImage->image_path === '') {
                return;
            }

            Storage::disk('public')->delete($galleryImage->image_path);
        });
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->orderBy('id');
    }
}
