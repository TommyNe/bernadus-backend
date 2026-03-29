<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompetitionResultResource;
use App\Models\CompetitionResult;
use Illuminate\Database\Eloquent\Builder;

class CompetitionResultController extends ResourceController
{
    protected string $modelClass = CompetitionResult::class;

    protected string $resourceClass = CompetitionResultResource::class;

    protected array $indexRelationships = ['category.competition', 'person'];

    protected array $showRelationships = ['category.competition.type', 'person.portrait'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('rank')->orderByDesc('score')->orderBy('winner_name');
    }
}
