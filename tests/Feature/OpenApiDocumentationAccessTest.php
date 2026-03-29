<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('serves the openapi json document', function () {
    $this->getJson('/openapi.json')
        ->assertSuccessful()
        ->assertJsonPath('openapi', '3.1.0')
        ->assertJsonPath('info.title', 'Bernadus Backend API')
        ->assertJsonPath('paths./api/pages.get.operationId', 'listPages');
});

it('serves the browser documentation page', function () {
    $this->get('/docs/api')
        ->assertSuccessful()
        ->assertSee('Bernadus API')
        ->assertSee('/openapi.json')
        ->assertSee('Endpunkte');
});
