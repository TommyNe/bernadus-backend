<?php

use App\Models\Competition;
use App\Models\CompetitionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('returns plaque shooting results including award rules for a given year', function () {
    $type = CompetitionType::factory()->create([
        'type_key' => 'plaque_shooting',
        'name' => 'Plakettenschießen',
    ]);

    $competition = Competition::factory()->for($type, 'type')->create([
        'year' => 2025,
        'title' => 'Plakettenschießen 2025',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $category = $competition->resultCategories()->create([
        'name' => 'Schützenklasse',
        'sort_order' => 10,
    ]);

    $category->results()->create([
        'winner_name' => 'Markus Thiel',
        'rank' => 1,
        'score' => 50,
        'score_text' => '50 Ringe',
    ]);

    $competition->plaqueAwardRules()->create([
        'rule_type' => 'ring_threshold',
        'age_from' => 18,
        'age_to' => 54,
        'required_score' => 48,
        'award_name' => 'Gold',
        'award_level' => 'Plakette',
        'sort_order' => 10,
    ]);

    $competition->milestoneAwards()->createMany([
        ['threshold' => '1 Gold', 'award' => 'Schützenschnur Grün', 'sort_order' => 10],
        ['threshold' => '100 Gold', 'award' => 'Besondere Plakette als Orden und zum Beispiel kleine Standuhr', 'sort_order' => 200],
    ]);

    $competition->scoreAwards()->createMany([
        ['age_group' => 'bis 55 Jahre', 'rings' => 48, 'award' => 'Gold', 'sort_order' => 10],
        ['age_group' => 'ab 70 Jahre', 'rings' => 28, 'award' => 'Bronze', 'sort_order' => 90],
    ]);

    $this->getJson('/api/competitions/plaque-shooting/2025')
        ->assertSuccessful()
        ->assertJsonPath('type_key', 'plaque_shooting')
        ->assertJsonPath('year', 2025)
        ->assertJsonPath('categories.0.name', 'Schützenklasse')
        ->assertJsonPath('categories.0.results.0.winner_name', 'Markus Thiel')
        ->assertJsonPath('award_rules.0.rule_type', 'ring_threshold')
        ->assertJsonPath('award_rules.0.required_score', 48)
        ->assertJsonPath('award_rules.0.award_name', 'Gold')
        ->assertJsonPath('milestoneAwards.0.threshold', '1 Gold')
        ->assertJsonPath('milestoneAwards.0.award', 'Schützenschnur Grün')
        ->assertJsonPath('milestoneAwards.1.threshold', '100 Gold')
        ->assertJsonPath('scoreAwards.0.ageGroup', 'bis 55 Jahre')
        ->assertJsonPath('scoreAwards.0.rings', '48')
        ->assertJsonPath('scoreAwards.0.award', 'Gold')
        ->assertJsonPath('scoreAwards.1.ageGroup', 'ab 70 Jahre')
        ->assertJsonPath('scoreAwards.1.rings', '28')
        ->assertJsonPath('scoreAwards.1.award', 'Bronze');
});

it('lists only published plaque shooting competitions', function () {
    $type = CompetitionType::factory()->create([
        'type_key' => 'plaque_shooting',
        'name' => 'Plakettenschießen',
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2024,
        'title' => 'Plakettenschießen 2024',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2025,
        'title' => 'Plakettenschießen 2025',
        'status' => 'draft',
        'published_at' => null,
    ]);

    $this->getJson('/api/competitions/plaque-shooting')
        ->assertSuccessful()
        ->assertJsonPath('type_key', 'plaque_shooting')
        ->assertJsonPath('competitions.0.year', 2024)
        ->assertJsonMissing(['title' => 'Plakettenschießen 2025']);
});

it('returns plaque shooting competitions even when publication columns are unavailable', function () {
    $type = CompetitionType::factory()->create([
        'type_key' => 'plaque_shooting',
        'name' => 'Plakettenschießen',
    ]);

    $competition = Competition::factory()->for($type, 'type')->create([
        'year' => 2025,
        'title' => 'Plakettenschießen 2025',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    Schema::shouldReceive('hasColumn')
        ->once()
        ->with('competitions', 'status')
        ->andReturn(false);

    $this->getJson('/api/competitions/plaque-shooting/2025')
        ->assertSuccessful()
        ->assertJsonPath('id', $competition->id)
        ->assertJsonPath('type_key', 'plaque_shooting')
        ->assertJsonPath('year', 2025);
});
