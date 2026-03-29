<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ExternalLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'link_key' => $this->link_key,
            'label' => $this->label,
            'url' => $this->url,
            'description' => $this->description,
            ...$this->timestamps($request),
        ];
    }
}
