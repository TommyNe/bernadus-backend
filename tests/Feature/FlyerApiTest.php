<?php

use App\Models\Flyer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('returns the current active flyer in the expected api format', function () {
    Flyer::factory()->inactive()->create([
        'title' => 'Frühere Version',
        'pdf_path' => 'flyers/archive.pdf',
        'uploaded_at' => now()->subWeek(),
    ]);

    Flyer::factory()->create([
        'title' => 'Aktueller Vereinsflyer',
        'pdf_path' => 'flyers/current.pdf',
        'original_filename' => 'vereinsflyer.pdf',
        'file_size' => 345_678,
        'uploaded_at' => now()->subHour(),
    ]);

    $response = $this->getJson('/api/flyer/current');

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Aktueller Vereinsflyer')
        ->assertJsonPath('data.pdf_url', Storage::disk('public')->url('flyers/current.pdf'))
        ->assertJsonPath('data.filename', 'vereinsflyer.pdf');

    expect(array_keys($response->json('data')))->toBe([
        'title',
        'pdf_url',
        'filename',
        'uploaded_at',
        'updated_at',
    ]);
});

it('keeps only the newest uploaded flyer active', function () {
    $firstFlyer = Flyer::factory()->create([
        'title' => 'Flyer 2025',
        'is_active' => true,
    ]);

    $secondFlyer = Flyer::factory()->create([
        'title' => 'Flyer 2026',
        'is_active' => true,
    ]);

    expect($firstFlyer->fresh()->is_active)->toBeFalse()
        ->and($secondFlyer->fresh()->is_active)->toBeTrue();

    $this->getJson('/api/flyer/current')
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Flyer 2026');
});

it('returns not found when no active flyer exists', function () {
    $this->getJson('/api/flyer/current')
        ->assertNotFound();
});
