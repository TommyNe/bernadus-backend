<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MediumResource;
use App\Models\Medium;
use Illuminate\Database\Eloquent\Builder;

class MediumController extends ResourceController
{
    protected string $modelClass = Medium::class;

    protected string $resourceClass = MediumResource::class;

    protected array $indexRelationships = ['portraits', 'chronicleImages'];

    protected array $showRelationships = ['portraits.roleAssignments.role', 'chronicleImages.chronicle'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderByDesc('created_at')->orderBy('filename');
    }
}
