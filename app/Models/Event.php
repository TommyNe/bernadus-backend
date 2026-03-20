<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'slug',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'all_day',
        'display_date_text',
        'month_label',
        'audience_text',
        'source_url',
        'external_ics_url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }
}
