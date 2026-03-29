<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompetitionResultCategoryResource;
use App\Models\CompetitionResultCategory;
use Illuminate\Database\Eloquent\Builder;

class CompetitionResultCategoryController extends ResourceController
{
    protected string $modelClass = CompetitionResultCategory::class;

    protected string $resourceClass = CompetitionResultCategoryResource::class;

    protected array $indexRelationships = ['competition.type', 'results.person'];

    protected array $showRelationships = ['competition.type', 'results.person'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }
}
