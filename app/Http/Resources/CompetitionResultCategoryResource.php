<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompetitionResultCategoryResource extends JsonResource
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
            'competition_id' => $this->competition_id,
            'name' => $this->name,
            'sort_order' => $this->sort_order,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            'results' => CompetitionResultResource::collection($this->whenLoaded('results')),
            ...$this->timestamps($request),
        ];
    }
}
