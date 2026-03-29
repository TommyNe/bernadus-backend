<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageSectionResource;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Builder;

class PageSectionController extends ResourceController
{
    protected string $modelClass = PageSection::class;

    protected string $resourceClass = PageSectionResource::class;

    protected array $indexRelationships = ['page'];

    protected array $showRelationships = ['page'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('title');
    }
}
