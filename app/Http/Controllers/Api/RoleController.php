<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class RoleController extends ResourceController
{
    protected string $modelClass = Role::class;

    protected string $resourceClass = RoleResource::class;

    protected array $indexRelationships = ['assignments.person.portrait'];

    protected array $showRelationships = ['assignments.person.portrait'];

    protected string $routeKey = 'role_key';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }
}
