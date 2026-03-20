<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street',
        'postal_code',
        'city',
        'notes',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('starts_at')->orderBy('sort_order');
    }
}
