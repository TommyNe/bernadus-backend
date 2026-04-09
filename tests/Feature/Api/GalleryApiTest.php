<?php

use App\Models\GalleryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('returns only active gallery images in the expected api format', function () {
    GalleryImage::factory()->inactive()->create([
        'title' => 'Verstecktes Bild',
        'image_path' => 'gallery/inactive.jpg',
        'sort_order' => 1,
    ]);

    GalleryImage::factory()->create([
        'title' => 'Jubilaeumsumzug',
        'image_path' => 'gallery/jubilaeumsumzug.jpg',
        'alt_text' => 'Schuetzen marschieren durch das Dorf',
        'sort_order' => 10,
    ]);

    GalleryImage::factory()->create([
        'title' => 'Koenigspaar',
        'image_path' => 'gallery/koenigspaar.jpg',
        'alt_text' => null,
        'sort_order' => 20,
    ]);

    $response = $this->getJson('/api/gallery');

    $response
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.title', 'Jubilaeumsumzug')
        ->assertJsonPath('data.0.image_url', Storage::disk('public')->url('gallery/jubilaeumsumzug.jpg'))
        ->assertJsonPath('data.0.sort_order', 10)
        ->assertJsonPath('data.0.alt_text', 'Schuetzen marschieren durch das Dorf')
        ->assertJsonPath('data.0.is_active', true)
        ->assertJsonPath('data.1.title', 'Koenigspaar')
        ->assertJsonPath('data.1.image_url', Storage::disk('public')->url('gallery/koenigspaar.jpg'))
        ->assertJsonPath('data.1.sort_order', 20)
        ->assertJsonPath('data.1.alt_text', null)
        ->assertJsonPath('data.1.is_active', true);

    expect(array_keys($response->json('data.0')))->toBe([
        'title',
        'image_url',
        'sort_order',
        'alt_text',
        'is_active',
    ]);
});
