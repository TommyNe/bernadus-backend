<?php

use App\Models\Competition;
use App\Models\CompetitionMilestoneAward;
use App\Models\CompetitionResult;
use App\Models\CompetitionResultCategory;
use App\Models\CompetitionScoreAward;
use App\Models\CompetitionType;
use Database\Seeders\ContentFoundationSeeder;
use Database\Seeders\PlaqueShootingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds fake plaque shooting competitions with linked events', function () {
    app(ContentFoundationSeeder::class)->run();
    app(PlaqueShootingSeeder::class)->run();

    $competitionType = CompetitionType::query()
        ->where('type_key', 'plaque_shooting')
        ->first();

    expect($competitionType)->not->toBeNull();

    $competitions = Competition::query()
        ->where('competition_type_id', $competitionType->id)
        ->with(['event', 'resultCategories.results'])
        ->orderBy('year')
        ->get();

    expect($competitions)->toHaveCount(4)
        ->and($competitions->pluck('year')->all())->toBe([2023, 2024, 2025, 2026])
        ->and($competitions[2]->title)->toBe('Plakettenschießen 2025')
        ->and($competitions[2]->event?->slug)->toBe('plakettenschiessen-2025')
        ->and($competitions[2]->resultCategories)->toHaveCount(2)
        ->and($competitions[2]->resultCategories[0]->results)->toHaveCount(3)
        ->and($competitions[2]->milestoneAwards()->count())->toBe(20)
        ->and($competitions[2]->scoreAwards()->count())->toBe(9);
});

it('can run the plaque shooting seeder repeatedly without duplicating rows', function () {
    app(ContentFoundationSeeder::class)->run();
    $seeder = app(PlaqueShootingSeeder::class);

    $seeder->run();
    $seeder->run();

    expect(Competition::query()->count())->toBe(4)
        ->and(CompetitionResultCategory::query()->count())->toBe(8)
        ->and(CompetitionResult::query()->count())->toBe(24)
        ->and(CompetitionMilestoneAward::query()->count())->toBe(80)
        ->and(CompetitionScoreAward::query()->count())->toBe(36);
});
