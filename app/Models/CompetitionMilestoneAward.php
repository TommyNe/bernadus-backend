<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionMilestoneAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'threshold',
        'award',
        'sort_order',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
