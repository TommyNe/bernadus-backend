<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PageSectionResource extends JsonResource
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
            'page_id' => $this->page_id,
            'section_key' => $this->section_key,
            'section_type' => $this->section_type,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'data' => $this->data,
            'sort_order' => $this->sort_order,
            'page' => new PageResource($this->whenLoaded('page')),
            ...$this->timestamps($request),
        ];
    }
}
