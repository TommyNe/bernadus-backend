<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ChronicleEntryResource;
use App\Models\ChronicleEntry;
use Illuminate\Database\Eloquent\Builder;

class ChronicleEntryController extends ResourceController
{
    protected string $modelClass = ChronicleEntry::class;

    protected string $resourceClass = ChronicleEntryResource::class;

    protected array $indexRelationships = ['chronicle', 'image'];

    protected array $showRelationships = ['chronicle', 'image'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderByDesc('year')->orderBy('sort_order');
    }
}
