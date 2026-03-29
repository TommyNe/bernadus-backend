<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ExternalLinkResource;
use App\Models\ExternalLink;
use Illuminate\Database\Eloquent\Builder;

class ExternalLinkController extends ResourceController
{
    protected string $modelClass = ExternalLink::class;

    protected string $resourceClass = ExternalLinkResource::class;

    protected string $routeKey = 'link_key';

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('label');
    }
}
