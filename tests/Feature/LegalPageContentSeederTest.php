<?php

use App\Models\Page;
use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('legal pages', [
    ['impressum', 'Angaben gemäß § 5 TMG', 'Impressum | St. Bernadus Tinnen'],
    ['datenschutz', 'Datenschutzhinweise', 'Datenschutz | St. Bernadus Tinnen'],
]);

it('seeds editable legal pages and exposes them via the page api', function (string $slug, string $sectionTitle, string $metaTitle) {
    app(ContentFoundationSeeder::class)->run();

    $page = Page::query()
        ->where('slug', $slug)
        ->with('sections')
        ->firstOrFail();

    expect($page->meta_title)->toBe($metaTitle)
        ->and($page->status)->toBe('published')
        ->and($page->sections)->toHaveCount(2)
        ->and($page->sections[0]->title)->toBe($sectionTitle);

    $this->getJson("/api/pages/{$slug}")
        ->assertSuccessful()
        ->assertJsonPath('data.slug', $slug)
        ->assertJsonPath('data.title', ucfirst($slug))
        ->assertJsonPath('data.meta_title', $metaTitle)
        ->assertJsonPath('data.sections.0.title', $sectionTitle);
})->with('legal pages');
