<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PageResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'nav_label' => $this->nav_label,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'published_at' => $this->serializeDate($this->published_at),
            'sort_order' => $this->sort_order,
            'sections' => PageSectionResource::collection($this->whenLoaded('sections')),
            ...$this->timestamps($request),
        ];
    }
}
