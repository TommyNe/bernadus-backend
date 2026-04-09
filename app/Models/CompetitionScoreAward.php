<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionScoreAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'age_group',
        'rings',
        'award',
        'sort_order',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
