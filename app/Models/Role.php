<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_key',
        'name',
        'description',
        'sort_order',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(RoleAssignment::class)->orderBy('sort_order');
    }
}
