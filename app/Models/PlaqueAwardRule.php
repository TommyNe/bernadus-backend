<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaqueAwardRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'rule_type',
        'age_from',
        'age_to',
        'required_score',
        'required_gold_count',
        'award_name',
        'award_level',
        'sort_order',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
