<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VenueResource;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Builder;

class VenueController extends ResourceController
{
    protected string $modelClass = Venue::class;

    protected string $resourceClass = VenueResource::class;

    protected array $indexRelationships = ['events'];

    protected array $showRelationships = ['events.competitions.type'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('name');
    }
}
