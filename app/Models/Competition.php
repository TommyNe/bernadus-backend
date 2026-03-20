<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_type_id',
        'event_id',
        'year',
        'title',
        'description',
        'source_url',
        'sort_order',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(CompetitionType::class, 'competition_type_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function resultCategories(): HasMany
    {
        return $this->hasMany(CompetitionResultCategory::class)->orderBy('sort_order');
    }

    public function plaqueAwardRules(): HasMany
    {
        return $this->hasMany(PlaqueAwardRule::class)->orderBy('sort_order');
    }
}
