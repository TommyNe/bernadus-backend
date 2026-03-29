<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlaqueAwardRuleResource;
use App\Models\PlaqueAwardRule;
use Illuminate\Database\Eloquent\Builder;

class PlaqueAwardRuleController extends ResourceController
{
    protected string $modelClass = PlaqueAwardRule::class;

    protected string $resourceClass = PlaqueAwardRuleResource::class;

    protected array $indexRelationships = ['competition.type', 'competition.event'];

    protected array $showRelationships = ['competition.type', 'competition.event.venue'];

    protected function applyIndexOrdering(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('award_name');
    }
}
