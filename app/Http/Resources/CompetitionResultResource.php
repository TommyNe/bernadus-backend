<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompetitionResultResource extends JsonResource
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
            'competition_result_category_id' => $this->competition_result_category_id,
            'person_id' => $this->person_id,
            'winner_name' => $this->winner_name,
            'rank' => $this->rank,
            'score' => $this->score,
            'score_text' => $this->score_text,
            'category' => new CompetitionResultCategoryResource($this->whenLoaded('category')),
            'person' => new PersonResource($this->whenLoaded('person')),
            ...$this->timestamps($request),
        ];
    }
}
