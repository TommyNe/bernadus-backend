<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'image_url' => Storage::disk('public')->url($this->image_path),
            'sort_order' => $this->sort_order,
            'alt_text' => $this->alt_text,
            'is_active' => $this->is_active,
        ];
    }
}
