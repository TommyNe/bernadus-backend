<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserController extends ResourceController
{
    protected string $modelClass = User::class;

    protected string $resourceClass = UserResource::class;

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('name');
    }
}
