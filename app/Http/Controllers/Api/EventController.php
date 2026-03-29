<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventController extends ResourceController
{
    protected string $modelClass = Event::class;

    protected string $resourceClass = EventResource::class;

    protected array $indexRelationships = ['venue', 'competitions.type'];

    protected array $showRelationships = ['venue', 'competitions.type', 'competitions.resultCategories.results.person', 'competitions.plaqueAwardRules'];

    protected string $routeKey = 'slug';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('starts_at')->orderBy('sort_order')->orderBy('title');
    }
}
