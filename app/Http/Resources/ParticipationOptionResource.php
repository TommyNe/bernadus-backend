<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipationOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'path' => $this->path,
            'summary' => $this->summary,
            'content' => $this->content,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}
