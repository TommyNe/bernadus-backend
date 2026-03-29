<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PersonResource extends JsonResource
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
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'portrait_media_id' => $this->portrait_media_id,
            'notes' => $this->notes,
            'portrait' => new MediumResource($this->whenLoaded('portrait')),
            'role_assignments' => RoleAssignmentResource::collection($this->whenLoaded('roleAssignments')),
            'competition_results' => CompetitionResultResource::collection($this->whenLoaded('competitionResults')),
            ...$this->timestamps($request),
        ];
    }
}
