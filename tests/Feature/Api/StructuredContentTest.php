<?php

use App\Models\ClubEvent;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns active club events ordered by featured flag and start date', function () {
    ClubEvent::factory()->create([
        'title' => 'Spaeter Termin',
        'slug' => 'spaeter-termin',
        'starts_at' => now()->addDays(10),
        'is_featured' => false,
    ]);

    ClubEvent::factory()->create([
        'title' => 'Hervorgehobener Termin',
        'slug' => 'hervorgehobener-termin',
        'starts_at' => now()->addDays(20),
        'is_featured' => true,
    ]);

    ClubEvent::factory()->create([
        'title' => 'Inaktiver Termin',
        'slug' => 'inaktiver-termin',
        'starts_at' => now()->addDays(1),
        'is_active' => false,
    ]);

    $response = $this->getJson('/api/events');

    $response
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.slug', 'hervorgehobener-termin')
        ->assertJsonPath('data.1.slug', 'spaeter-termin');
});

it('returns a single active club event by slug', function () {
    $event = ClubEvent::factory()->create([
        'title' => 'Sommerfest',
        'slug' => 'sommerfest',
    ]);

    $this->getJson('/api/events/'.$event->slug)
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Sommerfest');
});

it('returns active team members grouped by team type and sort order', function () {
    TeamMember::factory()->create([
        'name' => 'Beauftragte B',
        'slug' => 'beauftragte-b',
        'team_type' => 'delegate',
        'sort_order' => 1,
    ]);

    TeamMember::factory()->create([
        'name' => 'Vorstand A',
        'slug' => 'vorstand-a',
        'team_type' => 'board',
        'sort_order' => 5,
    ]);

    TeamMember::factory()->create([
        'name' => 'Vorstand Z',
        'slug' => 'vorstand-z',
        'team_type' => 'board',
        'sort_order' => 9,
        'is_active' => false,
    ]);

    $response = $this->getJson('/api/team-members');

    $response
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.slug', 'vorstand-a')
        ->assertJsonPath('data.0.team_type_label', 'Vorstand')
        ->assertJsonPath('data.1.slug', 'beauftragte-b');
});

it('returns a single active team member by slug', function () {
    $member = TeamMember::factory()->create([
        'name' => 'Maria Beckmann',
        'slug' => 'maria-beckmann',
        'team_type' => 'board',
    ]);

    $this->getJson('/api/team-members/'.$member->slug)
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Maria Beckmann')
        ->assertJsonPath('data.team_type_label', 'Vorstand');
});
