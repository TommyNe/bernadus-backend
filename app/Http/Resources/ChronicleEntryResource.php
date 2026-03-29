<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ChronicleEntryResource extends JsonResource
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
            'chronicle_id' => $this->chronicle_id,
            'year' => $this->year,
            'title' => $this->title,
            'headline' => $this->headline,
            'pair_text' => $this->pair_text,
            'secondary_text' => $this->secondary_text,
            'image_media_id' => $this->image_media_id,
            'external_image_url' => $this->external_image_url,
            'source_url' => $this->source_url,
            'is_highlighted' => $this->is_highlighted,
            'sort_order' => $this->sort_order,
            'chronicle' => new ChronicleResource($this->whenLoaded('chronicle')),
            'image' => new MediumResource($this->whenLoaded('image')),
            ...$this->timestamps($request),
        ];
    }
}
