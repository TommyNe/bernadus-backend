<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurrentFlyerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'pdf_url' => Storage::disk('public')->url($this->pdf_path),
            'filename' => $this->original_filename,
            'uploaded_at' => $this->serializeDate($this->uploaded_at),
            'updated_at' => $this->serializeDate($this->updated_at),
        ];
    }
}
