<?php

use App\Models\Competition;
use App\Models\CompetitionType;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns trophy shooting results for a given year', function () {
    $trophyType = CompetitionType::factory()->create([
        'type_key' => 'trophy_shooting',
        'name' => 'Pokalschießen',
    ]);

    $otherType = CompetitionType::factory()->create([
        'type_key' => 'plaque_shooting',
        'name' => 'Plakettenschießen',
    ]);

    $competition = Competition::factory()->for($trophyType, 'type')->create([
        'year' => 2025,
        'title' => 'Pokalschießen 2025',
    ]);

    Competition::factory()->for($otherType, 'type')->create([
        'year' => 2025,
        'title' => 'Plakettenschießen 2025',
    ]);

    $seniorCategory = $competition->resultCategories()->create([
        'name' => 'Senioren',
        'sort_order' => 2,
    ]);

    $womenCategory = $competition->resultCategories()->create([
        'name' => 'Damen',
        'sort_order' => 1,
    ]);

    $seniorCategory->results()->createMany([
        ['winner_name' => 'Bernd Otten', 'rank' => 3],
        ['winner_name' => 'Heinz Klene', 'rank' => 1],
        ['winner_name' => 'Helmut Efken', 'rank' => 2],
    ]);

    $womenCategory->results()->create([
        'winner_name' => 'Anna Becker',
        'rank' => 1,
        'score' => 95.5,
        'score_text' => '95,5 Ringe',
    ]);

    $this->getJson('/api/competitions/trophy-shooting/2025')
        ->assertSuccessful()
        ->assertJsonPath('type_key', 'trophy_shooting')
        ->assertJsonPath('year', 2025)
        ->assertJsonPath('title', 'Pokalschießen 2025')
        ->assertJsonPath('status', 'published')
        ->assertJsonPath('categories.0.name', 'Damen')
        ->assertJsonPath('categories.0.results.0.winner_name', 'Anna Becker')
        ->assertJsonPath('categories.1.name', 'Senioren')
        ->assertJsonPath('categories.1.results.0.rank', 1)
        ->assertJsonPath('categories.1.results.0.winner_name', 'Heinz Klene')
        ->assertJsonPath('categories.1.results.1.rank', 2)
        ->assertJsonPath('categories.1.results.2.rank', 3);
});

it('lists published trophy shooting competitions ordered by year', function () {
    $type = CompetitionType::factory()->create([
        'type_key' => 'trophy_shooting',
        'name' => 'Pokalschießen',
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2024,
        'title' => 'Pokalschießen 2024',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2025,
        'title' => 'Pokalschießen 2025',
        'status' => 'draft',
        'published_at' => null,
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2026,
        'title' => 'Pokalschießen 2026',
        'status' => 'published',
        'published_at' => now()->subHour(),
    ]);

    $this->getJson('/api/competitions/trophy-shooting')
        ->assertSuccessful()
        ->assertJsonPath('type_key', 'trophy_shooting')
        ->assertJsonPath('competitions.0.year', 2026)
        ->assertJsonPath('competitions.1.year', 2024)
        ->assertJsonMissing(['title' => 'Pokalschießen 2025']);
});

it('returns not found when no trophy shooting exists for the requested year', function () {
    Competition::factory()->create([
        'year' => 2025,
        'title' => 'Beliebiger Wettbewerb',
    ]);

    $this->getJson('/api/competitions/trophy-shooting/2025')
        ->assertNotFound();
});

it('allows admins to create a trophy shooting competition', function () {
    $admin = User::factory()->admin()->create();
    $person = Person::factory()->create([
        'display_name' => 'Heinz Klene',
    ]);

    $this->actingAs($admin)
        ->postJson('/api/admin/competitions/trophy-shooting', [
            'year' => 2025,
            'title' => 'Pokalschießen 2025',
            'description' => 'Ergebnisse vom Königsschießen.',
            'source_url' => 'https://example.com/pokal-2025',
            'categories' => [
                [
                    'name' => 'Senioren',
                    'results' => [
                        [
                            'rank' => 1,
                            'winner_name' => 'Heinz Klene',
                            'person_id' => $person->id,
                            'score' => 96.4,
                            'score_text' => '96,4 Ringe',
                        ],
                        [
                            'rank' => 2,
                            'winner_name' => 'Helmut Efken',
                        ],
                    ],
                ],
            ],
        ])
        ->assertCreated()
        ->assertJsonPath('message', 'Competition created successfully');

    $competitionType = CompetitionType::query()->where('type_key', 'trophy_shooting')->first();

    expect($competitionType)->not->toBeNull();

    $competition = Competition::query()
        ->where('competition_type_id', $competitionType->id)
        ->where('year', 2025)
        ->first();

    expect($competition)->not->toBeNull();

    $this->assertDatabaseHas('competition_result_categories', [
        'competition_id' => $competition->id,
        'name' => 'Senioren',
    ]);

    $categoryId = $competition->resultCategories()->value('id');

    $this->assertDatabaseHas('competition_results', [
        'competition_result_category_id' => $categoryId,
        'winner_name' => 'Heinz Klene',
        'rank' => 1,
        'person_id' => $person->id,
    ]);
});

it('rejects trophy shooting creation for non admins and duplicate years', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/admin/competitions/trophy-shooting', [
            'year' => 2025,
            'title' => 'Pokalschießen 2025',
            'categories' => [
                [
                    'name' => 'Senioren',
                    'results' => [
                        ['rank' => 1, 'winner_name' => 'Heinz Klene'],
                    ],
                ],
            ],
        ])
        ->assertForbidden();

    $type = CompetitionType::factory()->create([
        'type_key' => 'trophy_shooting',
        'name' => 'Pokalschießen',
    ]);

    Competition::factory()->for($type, 'type')->create([
        'year' => 2025,
        'title' => 'Pokalschießen 2025',
    ]);

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->postJson('/api/admin/competitions/trophy-shooting', [
            'year' => 2025,
            'title' => 'Pokalschießen 2025',
            'categories' => [
                [
                    'name' => 'Senioren',
                    'results' => [
                        ['rank' => 1, 'winner_name' => 'Heinz Klene'],
                    ],
                ],
            ],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['year']);
});
