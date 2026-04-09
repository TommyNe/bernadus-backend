<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompetitionYearRequest;
use App\Http\Resources\ShootingCompetitionResource;
use App\Models\Competition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlaqueShootingCompetitionController extends Controller
{
    public function index(): JsonResponse
    {
        $competitions = $this->competitionQuery()->get();

        return response()->json([
            'type_key' => 'plaque_shooting',
            'title' => 'Plakettenschießen',
            'competitions' => ShootingCompetitionResource::collection($competitions)->resolve(),
        ]);
    }

    public function show(CompetitionYearRequest $request): ShootingCompetitionResource
    {
        $competition = $this->competitionQuery()
            ->where('year', (int) $request->validated('year'))
            ->first();

        if ($competition === null) {
            throw new NotFoundHttpException;
        }

        return new ShootingCompetitionResource($competition);
    }

    protected function competitionQuery(): Builder
    {
        return Competition::query()
            ->published()
            ->whereHas('type', function (Builder $query): void {
                $query->where('type_key', 'plaque_shooting');
            })
            ->with([
                'type',
                'event',
                'resultCategories.results.person',
                'plaqueAwardRules',
                'milestoneAwards',
                'scoreAwards',
            ])
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderBy('title');
    }
}
