<?php

namespace App\Models;

use Database\Factories\FlyerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Flyer extends Model
{
    /** @use HasFactory<FlyerFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'pdf_path',
        'original_filename',
        'mime_type',
        'file_size',
        'is_active',
        'uploaded_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (Flyer $flyer): void {
            $flyer->uploaded_at ??= now();

            if (! $flyer->is_active) {
                return;
            }

            static::query()->where('is_active', true)->update(['is_active' => false]);
        });

        static::updating(function (Flyer $flyer): void {
            if ($flyer->isDirty('pdf_path')) {
                $flyer->uploaded_at = now();
            }

            if (! $flyer->is_active) {
                return;
            }

            static::query()
                ->whereKeyNot($flyer->getKey())
                ->where('is_active', true)
                ->update(['is_active' => false]);
        });

        static::updated(function (Flyer $flyer): void {
            if (! $flyer->wasChanged('pdf_path')) {
                return;
            }

            $originalPdfPath = $flyer->getOriginal('pdf_path');

            if (! is_string($originalPdfPath) || $originalPdfPath === '' || $originalPdfPath === $flyer->pdf_path) {
                return;
            }

            Storage::disk('public')->delete($originalPdfPath);
        });

        static::deleted(function (Flyer $flyer): void {
            if (! is_string($flyer->pdf_path) || $flyer->pdf_path === '') {
                return;
            }

            Storage::disk('public')->delete($flyer->pdf_path);
        });
    }

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_active' => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query
            ->active()
            ->orderByDesc('uploaded_at')
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }
}
