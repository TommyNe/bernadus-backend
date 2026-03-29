<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoleAssignmentResource;
use App\Models\RoleAssignment;
use Illuminate\Database\Eloquent\Builder;

class RoleAssignmentController extends ResourceController
{
    protected string $modelClass = RoleAssignment::class;

    protected string $resourceClass = RoleAssignmentResource::class;

    protected array $indexRelationships = ['person.portrait', 'role'];

    protected array $showRelationships = ['person.portrait', 'role'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderByDesc('is_current')->orderBy('sort_order');
    }
}
