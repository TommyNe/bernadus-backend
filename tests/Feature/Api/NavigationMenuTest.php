<?php

use Database\Seeders\NavigationItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the seeded navigation tree', function () {
    app(NavigationItemSeeder::class)->run();

    $response = $this->getJson('/api/navigation');

    $response
        ->assertSuccessful()
        ->assertJsonCount(7, 'data')
        ->assertJsonPath('data.0.title', 'Start')
        ->assertJsonPath('data.1.title', 'Über uns')
        ->assertJsonPath('data.1.children.0.title', 'Verein & Geschichte')
        ->assertJsonPath('data.1.children.4.slug', 'vereinshymne')
        ->assertJsonPath('data.6.children.0.path', '/kontakt/kontaktformular-info');
});

it('returns a single navigation item by slug', function () {
    app(NavigationItemSeeder::class)->run();

    $response = $this->getJson('/api/navigation/mitmachen');

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Mitmachen')
        ->assertJsonPath('data.slug', 'mitmachen')
        ->assertJsonCount(3, 'data.children')
        ->assertJsonPath('data.children.2.slug', 'whatsapp-newsletter');
});
