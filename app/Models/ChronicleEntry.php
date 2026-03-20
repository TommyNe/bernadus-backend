<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChronicleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'chronicle_id',
        'year',
        'title',
        'headline',
        'pair_text',
        'secondary_text',
        'image_media_id',
        'external_image_url',
        'source_url',
        'is_highlighted',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_highlighted' => 'boolean',
        ];
    }

    public function chronicle(): BelongsTo
    {
        return $this->belongsTo(Chronicle::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Medium::class, 'image_media_id');
    }
}
