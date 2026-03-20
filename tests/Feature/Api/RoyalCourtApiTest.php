<?php

use App\Models\RoyalCourt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('groups kings into recent, archive and child kings', function () {
    Storage::fake('public');

    RoyalCourt::factory()->create([
        'slug' => 'koenig-2024',
        'court_type' => 'king',
        'reign_year' => 2024,
        'ruler_name' => 'Koenig 2024',
        'image_path' => 'royal-courts/koenig-2024.jpg',
    ]);

    RoyalCourt::factory()->create([
        'slug' => 'koenig-1960',
        'court_type' => 'king',
        'reign_year' => 1960,
        'ruler_name' => 'Koenig 1960',
    ]);

    RoyalCourt::factory()->create([
        'slug' => 'kinderkoenig-2010',
        'court_type' => 'child_king',
        'reign_year' => 2010,
        'ruler_name' => 'Kinderkoenig 2010',
    ]);

    $this->getJson('/api/royal-courts')
        ->assertSuccessful()
        ->assertJsonPath('data.archive_cutoff_year', RoyalCourt::archiveCutoffYear())
        ->assertJsonCount(1, 'data.recent_kings')
        ->assertJsonCount(1, 'data.archived_kings')
        ->assertJsonCount(1, 'data.child_kings')
        ->assertJsonPath('data.counts.recent_kings', 1)
        ->assertJsonPath('data.counts.archived_kings', 1)
        ->assertJsonPath('data.counts.child_kings', 1)
        ->assertJsonPath('data.recent_kings.0.slug', 'koenig-2024')
        ->assertJsonPath('data.recent_kings.0.image_path', 'royal-courts/koenig-2024.jpg')
        ->assertJsonPath('data.recent_kings.0.image_url', Storage::disk('public')->url('royal-courts/koenig-2024.jpg'))
        ->assertJsonPath('data.archived_kings.0.slug', 'koenig-1960')
        ->assertJsonPath('data.child_kings.0.slug', 'kinderkoenig-2010');
});

it('returns a single royal court entry by slug', function () {
    Storage::fake('public');

    $entry = RoyalCourt::factory()->create([
        'slug' => 'koenig-2025',
        'court_type' => 'king',
        'reign_year' => 2025,
        'ruler_name' => 'Koenig 2025',
        'court_name' => 'Koenigin 2025',
        'image_path' => 'royal-courts/koenig-2025.jpg',
    ]);

    $this->getJson('/api/royal-courts/'.$entry->slug)
        ->assertSuccessful()
        ->assertJsonPath('data.ruler_name', 'Koenig 2025')
        ->assertJsonPath('data.court_name', 'Koenigin 2025')
        ->assertJsonPath('data.image_path', 'royal-courts/koenig-2025.jpg')
        ->assertJsonPath('data.image_url', Storage::disk('public')->url('royal-courts/koenig-2025.jpg'))
        ->assertJsonPath('data.is_archived', false);
});
