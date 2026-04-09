<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers the project api routes', function () {
    $routes = collect(app('router')->getRoutes()->getRoutesByName())->keys();

    expect($routes)->toContain(
        'api.pages.index',
        'api.pages.content',
        'api.page-sections.show',
        'api.external-links.show',
        'api.venues.index',
        'api.events.show',
        'api.competition-types.show',
        'api.competitions.index',
        'api.competition-result-categories.show',
        'api.competition-results.index',
        'api.plaque-award-rules.show',
        'api.chronicles.index',
        'api.chronicle-entries.show',
        'api.media.index',
        'api.people.show',
        'api.roles.index',
        'api.role-assignments.show',
        'api.users.index',
    );
});

it('returns not found for missing records', function () {
    $this->getJson('/api/pages/unbekannt')->assertNotFound();
    $this->getJson('/api/external-links/unbekannt')->assertNotFound();
    $this->getJson('/api/events/unbekannt')->assertNotFound();
});
