<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ChronicleResource;
use App\Models\Chronicle;
use Illuminate\Database\Eloquent\Builder;

class ChronicleController extends ResourceController
{
    protected string $modelClass = Chronicle::class;

    protected string $resourceClass = ChronicleResource::class;

    protected array $indexRelationships = ['entries.image'];

    protected array $showRelationships = ['entries.image'];

    protected string $routeKey = 'chronicle_key';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('title');
    }
}
