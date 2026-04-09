<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TrophyShootingCompetitionResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year' => $this->year,
            'title' => $this->title,
            'description' => $this->description,
            'source_url' => $this->source_url,
            'categories' => $this->resultCategories->map(function ($category): array {
                return [
                    'name' => $category->name,
                    'results' => $category->results->map(function ($result): array {
                        return [
                            'rank' => $result->rank,
                            'winner_name' => $result->winner_name,
                            'score' => $result->score,
                            'score_text' => $result->score_text,
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
        ];
    }
}
