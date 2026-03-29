<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompetitionTypeResource extends JsonResource
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
            'type_key' => $this->type_key,
            'name' => $this->name,
            'competitions' => CompetitionResource::collection($this->whenLoaded('competitions')),
            ...$this->timestamps($request),
        ];
    }
}
