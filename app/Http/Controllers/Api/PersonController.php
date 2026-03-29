<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;

class PersonController extends ResourceController
{
    protected string $modelClass = Person::class;

    protected string $resourceClass = PersonResource::class;

    protected array $indexRelationships = ['portrait', 'roleAssignments.role'];

    protected array $showRelationships = ['portrait', 'roleAssignments.role', 'competitionResults.category.competition'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('display_name');
    }
}
