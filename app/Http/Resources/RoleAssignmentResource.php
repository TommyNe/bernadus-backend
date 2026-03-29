<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RoleAssignmentResource extends JsonResource
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
            'person_id' => $this->person_id,
            'role_id' => $this->role_id,
            'label_override' => $this->label_override,
            'started_on' => $this->serializeDate($this->started_on),
            'ended_on' => $this->serializeDate($this->ended_on),
            'is_current' => $this->is_current,
            'sort_order' => $this->sort_order,
            'person' => new PersonResource($this->whenLoaded('person')),
            'role' => new RoleResource($this->whenLoaded('role')),
            ...$this->timestamps($request),
        ];
    }
}
