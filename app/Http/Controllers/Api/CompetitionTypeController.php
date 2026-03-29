<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompetitionTypeResource;
use App\Models\CompetitionType;
use Illuminate\Database\Eloquent\Builder;

class CompetitionTypeController extends ResourceController
{
    protected string $modelClass = CompetitionType::class;

    protected string $resourceClass = CompetitionTypeResource::class;

    protected array $indexRelationships = ['competitions.type', 'competitions.event'];

    protected array $showRelationships = ['competitions.resultCategories.results.person', 'competitions.event.venue'];

    protected string $routeKey = 'type_key';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('name');
    }
}
