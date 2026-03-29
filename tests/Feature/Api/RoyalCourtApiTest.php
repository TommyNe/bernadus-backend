<?php

use App\Models\Chronicle;
use App\Models\ChronicleEntry;
use App\Models\Medium;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns chronicles and chronicle entries with related media', function () {
    $chronicle = Chronicle::factory()->create([
        'chronicle_key' => 'shooting_kings',
        'title' => 'Chronik der Schuetzenkoenige',
    ]);

    $medium = Medium::factory()->create([
        'path' => 'chronicles/1980.jpg',
    ]);

    ChronicleEntry::factory()->for($chronicle)->for($medium, 'image')->create([
        'year' => 1980,
        'headline' => 'Jubilaeum',
    ]);

    $this->getJson('/api/chronicles')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.chronicle_key', 'shooting_kings')
        ->assertJsonPath('data.0.entries.0.year', 1980)
        ->assertJsonPath('data.0.entries.0.image.path', 'chronicles/1980.jpg');
});

it('returns chronicle and entry details by key and id', function () {
    $chronicle = Chronicle::factory()->create([
        'chronicle_key' => 'child_kings',
    ]);

    $entry = ChronicleEntry::factory()->for($chronicle)->create([
        'year' => 2012,
        'headline' => 'Kinderthron',
    ]);

    $this->getJson('/api/chronicles/child_kings')
        ->assertSuccessful()
        ->assertJsonPath('data.chronicle_key', 'child_kings');

    $this->getJson('/api/chronicle-entries/'.$entry->id)
        ->assertSuccessful()
        ->assertJsonPath('data.year', 2012)
        ->assertJsonPath('data.chronicle.chronicle_key', 'child_kings');
});
