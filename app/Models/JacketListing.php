<?php

namespace App\Models;

use Database\Factories\JacketListingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JacketListing extends Model
{
    /** @use HasFactory<JacketListingFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'details',
        'contact_hint',
        'status',
        'sort_order',
        'published_at',
    ];

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Entwurf',
            'published' => 'Veröffentlicht',
            'archived' => 'Archiviert',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            'Angebot' => 'Angebot',
            'Gesuch' => 'Gesuch',
            'Tausch' => 'Tausch',
        ];
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $builder): void {
                $builder
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->orderBy('id');
    }
}
