<?php

use App\Models\Competition;
use App\Models\CompetitionType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('includes trophy and plaque shooting demo data in the default database seeder', function () {
    $this->seed(DatabaseSeeder::class);

    $trophyShootingType = CompetitionType::query()
        ->where('type_key', 'trophy_shooting')
        ->first();

    $plaqueShootingType = CompetitionType::query()
        ->where('type_key', 'plaque_shooting')
        ->first();

    expect($trophyShootingType)->not->toBeNull()
        ->and($plaqueShootingType)->not->toBeNull()
        ->and(
            Competition::query()
                ->where('competition_type_id', $trophyShootingType->id)
                ->count()
        )->toBe(4)
        ->and(
            Competition::query()
                ->where('competition_type_id', $plaqueShootingType->id)
                ->count()
        )->toBe(4)
        ->and(
            Competition::query()
                ->where('competition_type_id', $trophyShootingType->id)
                ->where('year', 2026)
                ->value('title')
        )->toBe('Pokalschießen 2026')
        ->and(
            Competition::query()
                ->where('competition_type_id', $plaqueShootingType->id)
                ->where('year', 2026)
                ->value('title')
        )->toBe('Plakettenschießen 2026');
});
