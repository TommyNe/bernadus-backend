<?php

use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\CompetitionResultCategory;
use App\Models\CompetitionType;
use Database\Seeders\ContentFoundationSeeder;
use Database\Seeders\TrophyShootingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds fake trophy shooting competitions with categories and results', function () {
    app(ContentFoundationSeeder::class)->run();
    app(TrophyShootingSeeder::class)->run();

    $competitionType = CompetitionType::query()
        ->where('type_key', 'trophy_shooting')
        ->first();

    expect($competitionType)->not->toBeNull();

    $competitions = Competition::query()
        ->where('competition_type_id', $competitionType->id)
        ->with(['event', 'resultCategories.results'])
        ->orderBy('year')
        ->get();

    expect($competitions)->toHaveCount(4)
        ->and($competitions->pluck('year')->all())->toBe([2023, 2024, 2025, 2026])
        ->and($competitions[2]->title)->toBe('Pokalschießen 2025')
        ->and($competitions[2]->event?->slug)->toBe('pokalschiessen-2025')
        ->and($competitions[2]->resultCategories)->toHaveCount(3)
        ->and($competitions[2]->resultCategories[0]->results)->toHaveCount(3);
});

it('can run the trophy shooting seeder repeatedly without duplicating rows', function () {
    app(ContentFoundationSeeder::class)->run();
    $seeder = app(TrophyShootingSeeder::class);

    $seeder->run();
    $seeder->run();

    expect(Competition::query()->count())->toBe(4)
        ->and(CompetitionResultCategory::query()->count())->toBe(12)
        ->and(CompetitionResult::query()->count())->toBe(36);
});
