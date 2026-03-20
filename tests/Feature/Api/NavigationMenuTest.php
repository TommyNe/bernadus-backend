<?php

use Database\Seeders\NavigationItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the seeded navigation tree', function () {
    app(NavigationItemSeeder::class)->run();

    $response = $this->getJson('/api/navigation');

    $response
        ->assertSuccessful()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('data.0.title', 'Startseite')
        ->assertJsonPath('data.1.title', 'Über uns')
        ->assertJsonPath('data.1.children.0.title', 'Geschichte')
        ->assertJsonPath('data.2.title', 'Vorstand & Team')
        ->assertJsonPath('data.3.children.2.slug', 'plaketten-pokalschiessen')
        ->assertJsonPath('data.7.children.0.path', '/newsletter/whatsapp-newsletter')
        ->assertJsonPath('data.9.path', '/datenschutz');
});

it('returns a single navigation item by slug', function () {
    app(NavigationItemSeeder::class)->run();

    $response = $this->getJson('/api/navigation/mitglied-werden');

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Mitglied werden')
        ->assertJsonPath('data.slug', 'mitglied-werden')
        ->assertJsonCount(4, 'data.children')
        ->assertJsonPath('data.children.2.slug', 'antrag');
});
