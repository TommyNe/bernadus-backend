<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class JacketListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'details' => $this->details,
            'contact_hint' => $this->contact_hint,
            'sort_order' => $this->sort_order,
            'published_at' => $this->serializeDate($this->published_at),
        ];
    }
}
