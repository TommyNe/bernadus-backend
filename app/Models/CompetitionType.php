<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_key',
        'name',
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class)->orderByDesc('year')->orderBy('sort_order');
    }
}
