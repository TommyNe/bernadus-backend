<?php

namespace App\Models;

use Database\Factories\ContactEntryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactEntry extends Model
{
    /** @use HasFactory<ContactEntryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'path',
        'summary',
        'content',
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
