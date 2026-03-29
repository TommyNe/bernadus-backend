<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ChronicleResource extends JsonResource
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
            'chronicle_key' => $this->chronicle_key,
            'title' => $this->title,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'entries' => ChronicleEntryResource::collection($this->whenLoaded('entries')),
            ...$this->timestamps($request),
        ];
    }
}
