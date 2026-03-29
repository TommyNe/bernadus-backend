<?php

use App\Filament\Resources\Media\Pages\CreateMedium;
use App\Models\Medium;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('uploads images through the media filament resource', function () {
    Storage::fake('public');

    $adminUser = User::factory()->admin()->create();
    $image = UploadedFile::fake()->image('schuetzenfest.jpg', 1600, 1200)->size(256);

    $this->actingAs($adminUser);

    Livewire::test(CreateMedium::class)
        ->fillForm([
            'path' => $image,
            'title' => 'Schuetzenfest 2026',
            'alt_text' => 'Festfoto vom Umzug',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $medium = Medium::query()->sole();

    expect($medium->disk)->toBe('public');
    expect($medium->filename)->toBe('schuetzenfest.jpg');
    expect($medium->mime_type)->toBe('image/jpeg');
    expect($medium->extension)->toBe('jpg');
    expect($medium->width)->toBe(1600);
    expect($medium->height)->toBe(1200);
    expect($medium->size)->toBeGreaterThan(0);
    expect($medium->path)->toStartWith('media/');

    Storage::disk('public')->assertExists($medium->path);
});
