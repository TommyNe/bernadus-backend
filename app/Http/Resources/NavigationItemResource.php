<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'path' => $this->path,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'children' => NavigationItemResource::collection($this->whenLoaded('children')),
        ];
    }
}
