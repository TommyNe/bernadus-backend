<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PlaqueAwardRuleResource extends JsonResource
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
            'rule_type' => $this->rule_type,
            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'required_score' => $this->required_score,
            'required_gold_count' => $this->required_gold_count,
            'award_name' => $this->award_name,
            'award_level' => $this->award_level,
            'sort_order' => $this->sort_order,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            ...$this->timestamps($request),
        ];
    }
}
