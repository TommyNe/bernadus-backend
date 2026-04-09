<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompetitionYearRequest;
use App\Http\Requests\StoreTrophyShootingCompetitionRequest;
use App\Http\Resources\ShootingCompetitionResource;
use App\Models\Competition;
use App\Models\CompetitionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrophyShootingCompetitionController extends Controller
{
    public function index(): JsonResponse
    {
        $competitions = $this->competitionQuery()->get();

        return response()->json([
            'type_key' => 'trophy_shooting',
            'title' => 'Pokalschießen',
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

    public function store(StoreTrophyShootingCompetitionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $competition = DB::transaction(function () use ($validated): Competition {
            $competitionType = CompetitionType::query()->firstOrCreate(
                ['type_key' => 'trophy_shooting'],
                ['name' => 'Pokalschießen']
            );

            $competition = Competition::query()->create([
                'competition_type_id' => $competitionType->id,
                'year' => $validated['year'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'source_url' => $validated['source_url'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => 'published',
                'published_at' => now(),
            ]);

            foreach ($validated['categories'] as $categoryIndex => $categoryData) {
                $category = $competition->resultCategories()->create([
                    'name' => $categoryData['name'],
                    'sort_order' => $categoryData['sort_order'] ?? $categoryIndex,
                ]);

                foreach ($categoryData['results'] as $resultData) {
                    $category->results()->create([
                        'person_id' => $resultData['person_id'] ?? null,
                        'winner_name' => $resultData['winner_name'],
                        'rank' => $resultData['rank'],
                        'score' => $resultData['score'] ?? null,
                        'score_text' => $resultData['score_text'] ?? null,
                    ]);
                }
            }

            return $competition;
        });

        return response()->json([
            'message' => 'Competition created successfully',
            'id' => $competition->id,
        ], 201);
    }

    protected function competitionQuery(): Builder
    {
        return Competition::query()
            ->published()
            ->whereHas('type', function (Builder $query): void {
                $query->where('type_key', 'trophy_shooting');
            })
            ->with([
                'type',
                'event',
                'resultCategories.results.person',
            ])
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderBy('title');
    }
}
