<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompetitionResource;
use App\Models\Competition;
use Illuminate\Database\Eloquent\Builder;

class CompetitionController extends ResourceController
{
    protected string $modelClass = Competition::class;

    protected string $resourceClass = CompetitionResource::class;

    protected array $indexRelationships = ['type', 'event', 'resultCategories.results.person', 'plaqueAwardRules'];

    protected array $showRelationships = ['type', 'event.venue', 'resultCategories.results.person', 'plaqueAwardRules'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderByDesc('year')->orderBy('sort_order')->orderBy('title');
    }
}
