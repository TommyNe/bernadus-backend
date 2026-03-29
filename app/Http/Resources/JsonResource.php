<?php

namespace App\Http\Resources;

use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

abstract class JsonResource extends BaseJsonResource
{
    protected function serializeDate(?DateTimeInterface $date): ?string
    {
        return $date?->toAtomString();
    }

    /**
     * @return array<string, mixed>
     */
    protected function timestamps(Request $request): array
    {
        return [
            'created_at' => $this->serializeDate($this->resource->created_at ?? null),
            'updated_at' => $this->serializeDate($this->resource->updated_at ?? null),
        ];
    }
}
