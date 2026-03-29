<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class EventResource extends JsonResource
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
            'venue_id' => $this->venue_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->serializeDate($this->starts_at),
            'ends_at' => $this->serializeDate($this->ends_at),
            'all_day' => $this->all_day,
            'display_date_text' => $this->display_date_text,
            'month_label' => $this->month_label,
            'audience_text' => $this->audience_text,
            'source_url' => $this->source_url,
            'external_ics_url' => $this->external_ics_url,
            'sort_order' => $this->sort_order,
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'competitions' => CompetitionResource::collection($this->whenLoaded('competitions')),
            ...$this->timestamps($request),
        ];
    }
}
