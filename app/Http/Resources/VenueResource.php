<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class VenueResource extends JsonResource
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
            'name' => $this->name,
            'street' => $this->street,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'notes' => $this->notes,
            'events' => EventResource::collection($this->whenLoaded('events')),
            ...$this->timestamps($request),
        ];
    }
}
