<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RoleResource extends JsonResource
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
            'role_key' => $this->role_key,
            'name' => $this->name,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'assignments' => RoleAssignmentResource::collection($this->whenLoaded('assignments')),
            ...$this->timestamps($request),
        ];
    }
}
