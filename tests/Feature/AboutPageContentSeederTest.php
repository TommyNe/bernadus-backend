<?php

use App\Models\Page;
use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds the ueber uns page with editable sections', function () {
    app(ContentFoundationSeeder::class)->run();

    $page = Page::query()
        ->where('slug', 'ueber-uns')
        ->with('sections')
        ->firstOrFail();

    expect($page->meta_title)->toBe('Über uns | St. Bernadus Tinnen')
        ->and($page->sections)->toHaveCount(4)
        ->and($page->sections->pluck('section_key')->all())->toBe([
            'hero-about',
            'about-overview',
            'history-meaning',
            'about-highlights',
        ])
        ->and($page->sections[1]->data['items'])->toHaveCount(4)
        ->and($page->sections[1]->data['items'][0]['title'])->toBe('Chronik')
        ->and($page->sections[3]->data['items'][2]['link_url'])->toBe('https://www.schuetzenverein-tinnen.de/');

    $this->getJson('/api/pages/ueber-uns')
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Über uns')
        ->assertJsonPath('data.sections.0.section_key', 'hero-about')
        ->assertJsonPath('data.sections.1.data.items.3.title', 'Service')
        ->assertJsonPath('data.sections.3.data.items.2.link_label', 'Offizielle Website besuchen');
});
