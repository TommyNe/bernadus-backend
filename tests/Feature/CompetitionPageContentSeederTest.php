<?php

use App\Models\Page;
use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds the plaque and trophy competition page with editable sections', function () {
    app(ContentFoundationSeeder::class)->run();

    $page = Page::query()
        ->where('slug', 'veranstaltungen/plaketten-pokalschiessen')
        ->with('sections')
        ->firstOrFail();

    expect($page->sections)->toHaveCount(4)
        ->and($page->sections->pluck('section_key')->all())->toBe([
            'plaque_rules',
            'plaque_results_intro',
            'trophy_shooting',
            'trophy_results_intro',
        ])
        ->and($page->sections[0]->title)->toBe('Plakettenschießen')
        ->and($page->sections[2]->title)->toBe('Pokalschießen');

    $this->getJson('/api/pages')
        ->assertSuccessful()
        ->assertJsonFragment([
            'slug' => 'veranstaltungen/plaketten-pokalschiessen',
            'title' => 'Plaketten- und Pokalschießen',
        ]);
});
