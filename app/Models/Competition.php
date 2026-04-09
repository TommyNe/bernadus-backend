<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

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
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        if (! Schema::hasColumn($this->getTable(), 'status')) {
            return $query;
        }

        $query->where('status', 'published');

        if (! Schema::hasColumn($this->getTable(), 'published_at')) {
            return $query;
        }

        return $query->where(function (Builder $builder): void {
            $builder
                ->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        });
    }

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

    public function milestoneAwards(): HasMany
    {
        return $this->hasMany(CompetitionMilestoneAward::class)->orderBy('sort_order');
    }

    public function scoreAwards(): HasMany
    {
        return $this->hasMany(CompetitionScoreAward::class)->orderBy('sort_order');
    }
}
