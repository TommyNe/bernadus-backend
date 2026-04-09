<?php

use App\Filament\Resources\GalleryImages\Pages\CreateGalleryImage;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the gallery page without embedding gallery data in the inertia payload', function () {
    $this->get('/galerie')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('gallery')
            ->where('pageTitle', 'Galerie')
            ->where('galleryEndpoint', '/api/gallery')
            ->missing('galleryImages'),
        );
});

it('uploads gallery images through filament and removes the stored file on delete', function () {
    Storage::fake('public');

    $adminUser = User::factory()->admin()->create();
    $image = UploadedFile::fake()->image('galerie-2026.jpg', 1800, 1200)->size(320);

    $this->actingAs($adminUser);

    Livewire::test(CreateGalleryImage::class)
        ->fillForm([
            'title' => 'Sommerfest 2026',
            'image_path' => $image,
            'alt_text' => 'Besucher vor dem Festzelt',
            'sort_order' => 15,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $galleryImage = GalleryImage::query()->sole();
    $storedImagePath = $galleryImage->image_path;

    expect($galleryImage->title)->toBe('Sommerfest 2026')
        ->and($galleryImage->alt_text)->toBe('Besucher vor dem Festzelt')
        ->and($galleryImage->sort_order)->toBe(15)
        ->and($galleryImage->is_active)->toBeTrue()
        ->and($storedImagePath)->toStartWith('gallery/');

    Storage::disk('public')->assertExists($storedImagePath);

    $galleryImage->delete();

    Storage::disk('public')->assertMissing($storedImagePath);
});
