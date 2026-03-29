<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('documents every registered api route in the openapi spec', function () {
    $spec = json_decode(file_get_contents(base_path('openapi.json')), true, 512, JSON_THROW_ON_ERROR);

    $documentedPaths = collect(array_keys($spec['paths']))->sort()->values();

    $registeredPaths = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->getName() ?? '', 'api.'))
        ->map(fn ($route) => '/'.$route->uri())
        ->unique()
        ->sort()
        ->values();

    expect($spec['openapi'])->toBe('3.1.0')
        ->and($spec['info']['title'])->toBe('Bernadus Backend API')
        ->and($documentedPaths)->toEqual($registeredPaths)
        ->and($spec['components']['schemas'])->toHaveKeys([
            'Page',
            'Event',
            'Competition',
            'Chronicle',
            'Person',
            'User',
        ]);
});
