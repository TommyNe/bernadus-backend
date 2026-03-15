<?php

namespace App\Models;

use Database\Factories\NavigationItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    /** @use HasFactory<NavigationItemFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'path',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
