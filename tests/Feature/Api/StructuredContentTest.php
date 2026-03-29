<?php

use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\CompetitionResultCategory;
use App\Models\CompetitionType;
use App\Models\Event;
use App\Models\Person;
use App\Models\PlaqueAwardRule;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns venues and events with competition data', function () {
    $venue = Venue::factory()->create([
        'name' => 'Schuetzenhalle',
    ]);

    $event = Event::factory()->for($venue)->create([
        'slug' => 'fruehjahrsschiessen',
        'title' => 'Fruehjahrsschiessen',
    ]);

    $type = CompetitionType::factory()->create([
        'type_key' => 'pokal',
        'name' => 'Pokal',
    ]);

    Competition::factory()->for($type, 'type')->for($event)->create([
        'title' => 'Pokalrunde',
        'year' => 2026,
    ]);

    $this->getJson('/api/venues')
        ->assertSuccessful()
        ->assertJsonPath('data.0.name', 'Schuetzenhalle')
        ->assertJsonPath('data.0.events.0.slug', 'fruehjahrsschiessen');

    $this->getJson('/api/events/fruehjahrsschiessen')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'fruehjahrsschiessen')
        ->assertJsonPath('data.venue.name', 'Schuetzenhalle')
        ->assertJsonPath('data.competitions.0.title', 'Pokalrunde');
});

it('returns competition types and competitions by their route keys', function () {
    $type = CompetitionType::factory()->create([
        'type_key' => 'plakette',
        'name' => 'Plakette',
    ]);

    $competition = Competition::factory()->for($type, 'type')->create([
        'title' => 'Plakettenrunde',
        'year' => 2025,
    ]);

    $this->getJson('/api/competition-types/plakette')
        ->assertSuccessful()
        ->assertJsonPath('data.type_key', 'plakette')
        ->assertJsonPath('data.competitions.0.title', 'Plakettenrunde');

    $this->getJson('/api/competitions/'.$competition->id)
        ->assertSuccessful()
        ->assertJsonPath('data.id', $competition->id)
        ->assertJsonPath('data.type.type_key', 'plakette');
});

it('returns result categories, results and plaque award rules', function () {
    $competition = Competition::factory()->create([
        'title' => 'Koenigsschiessen',
    ]);

    $category = CompetitionResultCategory::factory()->for($competition)->create([
        'name' => 'Senioren',
    ]);

    $person = Person::factory()->create([
        'display_name' => 'Max Mustermann',
    ]);

    $result = CompetitionResult::factory()->for($category, 'category')->for($person)->create([
        'winner_name' => 'Max Mustermann',
        'rank' => 1,
    ]);

    $rule = PlaqueAwardRule::factory()->for($competition)->create([
        'award_name' => 'Goldene Plakette',
    ]);

    $this->getJson('/api/competition-result-categories/'.$category->id)
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Senioren')
        ->assertJsonPath('data.results.0.winner_name', 'Max Mustermann');

    $this->getJson('/api/competition-results/'.$result->id)
        ->assertSuccessful()
        ->assertJsonPath('data.rank', 1)
        ->assertJsonPath('data.person.display_name', 'Max Mustermann');

    $this->getJson('/api/plaque-award-rules/'.$rule->id)
        ->assertSuccessful()
        ->assertJsonPath('data.award_name', 'Goldene Plakette')
        ->assertJsonPath('data.competition.title', 'Koenigsschiessen');
});
