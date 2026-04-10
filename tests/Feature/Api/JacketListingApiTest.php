<?php

use App\Models\JacketListing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns only published jacket listings in the expected api format', function () {
    JacketListing::factory()->draft()->create([
        'title' => 'Interner Entwurf',
        'sort_order' => 1,
    ]);

    JacketListing::factory()->archived()->create([
        'title' => 'Archivierter Eintrag',
        'sort_order' => 2,
    ]);

    JacketListing::factory()->published()->create([
        'type' => 'Gesuch',
        'title' => 'Damenjacke Größe 40/42',
        'details' => 'Gesucht wird eine gepflegte Jacke für den Einstieg in den Verein.',
        'contact_hint' => 'Bitte Nachricht an die Redaktion.',
        'sort_order' => 10,
        'published_at' => null,
    ]);

    JacketListing::factory()->published()->create([
        'type' => 'Angebot',
        'title' => 'Schützenjacke Größe 50',
        'details' => 'Gut erhaltene Uniformjacke inklusive Schulterklappen.',
        'contact_hint' => null,
        'sort_order' => 20,
        'published_at' => now()->subDay(),
    ]);

    JacketListing::factory()->published()->create([
        'title' => 'Erst nächste Woche sichtbar',
        'sort_order' => 30,
        'published_at' => now()->addWeek(),
    ]);

    $response = $this->getJson('/api/jackenboerse');

    $response
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.type', 'Gesuch')
        ->assertJsonPath('data.0.title', 'Damenjacke Größe 40/42')
        ->assertJsonPath('data.0.details', 'Gesucht wird eine gepflegte Jacke für den Einstieg in den Verein.')
        ->assertJsonPath('data.0.contact_hint', 'Bitte Nachricht an die Redaktion.')
        ->assertJsonPath('data.0.sort_order', 10)
        ->assertJsonPath('data.0.published_at', null)
        ->assertJsonPath('data.1.type', 'Angebot')
        ->assertJsonPath('data.1.title', 'Schützenjacke Größe 50')
        ->assertJsonPath('data.1.contact_hint', null)
        ->assertJsonPath('data.1.sort_order', 20);

    expect(array_keys($response->json('data.0')))->toBe([
        'id',
        'type',
        'title',
        'details',
        'contact_hint',
        'sort_order',
        'published_at',
    ]);
});

it('returns published jacket listings by id and hides unpublished entries', function () {
    $publishedListing = JacketListing::factory()->published()->create([
        'type' => 'Tausch',
        'title' => 'Jacke Größe 54 gegen 52',
    ]);

    $draftListing = JacketListing::factory()->draft()->create([
        'title' => 'Nur intern sichtbar',
    ]);

    $this->getJson('/api/jackenboerse/'.$publishedListing->id)
        ->assertSuccessful()
        ->assertJsonPath('data.id', $publishedListing->id)
        ->assertJsonPath('data.type', 'Tausch')
        ->assertJsonPath('data.title', 'Jacke Größe 54 gegen 52');

    $this->getJson('/api/jackenboerse/'.$draftListing->id)
        ->assertNotFound();
});
