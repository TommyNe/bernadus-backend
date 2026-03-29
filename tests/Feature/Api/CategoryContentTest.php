<?php

use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\PageSection;
use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns seeded pages with sections', function () {
    app(ContentFoundationSeeder::class)->run();

    $page = Page::query()->where('slug', 'start')->firstOrFail();

    PageSection::factory()->for($page)->create([
        'section_key' => 'hero-start',
        'section_type' => 'hero',
        'title' => 'Willkommen',
        'sort_order' => 1,
    ]);

    PageSection::factory()->for($page)->create([
        'section_key' => 'cta-start',
        'section_type' => 'cta',
        'title' => 'Mitmachen',
        'sort_order' => 2,
    ]);

    $this->getJson('/api/pages')
        ->assertSuccessful()
        ->assertJsonCount(16, 'data')
        ->assertJsonPath('data.0.slug', 'start')
        ->assertJsonPath('data.0.sections.0.section_key', 'hero-start')
        ->assertJsonPath('data.0.sections.1.section_key', 'cta-start');
});

it('returns a single page by slug and page sections separately', function () {
    $page = Page::factory()->create([
        'slug' => 'satzung',
        'title' => 'Satzung',
        'sort_order' => 5,
    ]);

    $section = PageSection::factory()->for($page)->create([
        'section_key' => 'satzung-einleitung',
        'section_type' => 'rich_text',
        'sort_order' => 3,
    ]);

    $this->getJson('/api/pages/satzung')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'satzung')
        ->assertJsonPath('data.sections.0.section_key', 'satzung-einleitung');

    $this->getJson('/api/page-sections/'.$section->id)
        ->assertSuccessful()
        ->assertJsonPath('section_key', 'satzung-einleitung');
});

it('returns external links by key', function () {
    ExternalLink::factory()->create([
        'link_key' => 'verband',
        'label' => 'Verband',
    ]);

    ExternalLink::factory()->create([
        'link_key' => 'kalender',
        'label' => 'Kalender',
    ]);

    $this->getJson('/api/external-links')
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.label', 'Kalender')
        ->assertJsonPath('data.1.label', 'Verband');

    $this->getJson('/api/external-links/verband')
        ->assertSuccessful()
        ->assertJsonPath('data.link_key', 'verband')
        ->assertJsonPath('data.label', 'Verband');
});
