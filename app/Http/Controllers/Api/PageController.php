<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;

class PageController extends ResourceController
{
    protected string $modelClass = Page::class;

    protected string $resourceClass = PageResource::class;

    protected array $indexRelationships = ['sections'];

    protected array $showRelationships = ['sections'];

    protected string $routeKey = 'slug';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('title');
    }
}
