<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chronicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'chronicle_key',
        'title',
        'description',
        'sort_order',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(ChronicleEntry::class)->orderByDesc('year')->orderBy('sort_order');
    }
}
