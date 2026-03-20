<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionResultCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'name',
        'sort_order',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(CompetitionResult::class)->orderBy('rank');
    }
}
