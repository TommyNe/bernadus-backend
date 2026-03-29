<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompetitionResource extends JsonResource
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
            'competition_type_id' => $this->competition_type_id,
            'event_id' => $this->event_id,
            'year' => $this->year,
            'title' => $this->title,
            'description' => $this->description,
            'source_url' => $this->source_url,
            'sort_order' => $this->sort_order,
            'type' => new CompetitionTypeResource($this->whenLoaded('type')),
            'event' => new EventResource($this->whenLoaded('event')),
            'result_categories' => CompetitionResultCategoryResource::collection($this->whenLoaded('resultCategories')),
            'plaque_award_rules' => PlaqueAwardRuleResource::collection($this->whenLoaded('plaqueAwardRules')),
            ...$this->timestamps($request),
        ];
    }
}
