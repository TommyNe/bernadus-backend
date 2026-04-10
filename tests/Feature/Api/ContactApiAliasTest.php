<?php

use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the contact payload through the dedicated contact endpoint', function () {
    app(ContentFoundationSeeder::class)->run();

    $expectedPayload = $this->getJson('/api/pages/kontakt/content')
        ->assertSuccessful()
        ->json();

    $this->getJson('/api/contact')
        ->assertSuccessful()
        ->assertExactJson($expectedPayload);
});
