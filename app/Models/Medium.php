<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medium extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'disk',
        'path',
        'filename',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'title',
        'alt_text',
    ];

    public function portraits(): HasMany
    {
        return $this->hasMany(Person::class, 'portrait_media_id');
    }

    public function chronicleImages(): HasMany
    {
        return $this->hasMany(ChronicleEntry::class, 'image_media_id');
    }
}
