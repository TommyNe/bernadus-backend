<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ShootingCompetitionResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type_key' => $this->type?->type_key,
            'year' => $this->year,
            'title' => $this->title,
            'description' => $this->description,
            'source_url' => $this->source_url,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'published_at' => $this->serializeDate($this->published_at),
            'event' => $this->whenLoaded('event', function (): ?array {
                if ($this->event === null) {
                    return null;
                }

                return [
                    'id' => $this->event->id,
                    'slug' => $this->event->slug,
                    'title' => $this->event->title,
                    'display_date_text' => $this->event->display_date_text,
                    'starts_at' => $this->serializeDate($this->event->starts_at),
                    'ends_at' => $this->serializeDate($this->event->ends_at),
                    'month_label' => $this->event->month_label,
                    'audience_text' => $this->event->audience_text,
                ];
            }),
            'categories' => $this->resultCategories->map(function ($category): array {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'sort_order' => $category->sort_order,
                    'results' => $category->results->map(function ($result): array {
                        return [
                            'id' => $result->id,
                            'rank' => $result->rank,
                            'winner_name' => $result->winner_name,
                            'person_id' => $result->person_id,
                            'score' => $result->score,
                            'score_text' => $result->score_text,
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
            'award_rules' => $this->whenLoaded('plaqueAwardRules', fn (): array => $this->plaqueAwardRules
                ->map(function ($rule): array {
                    return [
                        'id' => $rule->id,
                        'rule_type' => $rule->rule_type,
                        'age_from' => $rule->age_from,
                        'age_to' => $rule->age_to,
                        'required_score' => $rule->required_score,
                        'required_gold_count' => $rule->required_gold_count,
                        'award_name' => $rule->award_name,
                        'award_level' => $rule->award_level,
                        'sort_order' => $rule->sort_order,
                    ];
                })
                ->values()
                ->all()),
            'milestoneAwards' => $this->when(
                $this->type?->type_key === 'plaque_shooting',
                fn (): array => $this->milestoneAwards
                    ->map(fn ($award): array => [
                        'threshold' => $award->threshold,
                        'award' => $award->award,
                    ])
                    ->values()
                    ->all()
            ),
            'scoreAwards' => $this->when(
                $this->type?->type_key === 'plaque_shooting',
                fn (): array => $this->scoreAwards
                    ->map(fn ($award): array => [
                        'ageGroup' => $award->age_group,
                        'rings' => (string) $award->rings,
                        'award' => $award->award,
                    ])
                    ->values()
                    ->all()
            ),
        ];
    }
}
