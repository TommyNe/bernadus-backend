<?php

use Database\Seeders\CategoryContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns all category tables grouped in one response', function () {
    app(CategoryContentSeeder::class)->run();

    $response = $this->getJson('/api/category-content');

    $response
        ->assertSuccessful()
        ->assertJsonCount(1, 'data.start')
        ->assertJsonCount(5, 'data.about')
        ->assertJsonCount(3, 'data.events')
        ->assertJsonCount(3, 'data.service_materials')
        ->assertJsonCount(1, 'data.gallery_honors')
        ->assertJsonCount(3, 'data.participation')
        ->assertJsonCount(1, 'data.contact')
        ->assertJsonPath('data.about.0.title', 'Verein & Geschichte')
        ->assertJsonPath('data.events.1.slug', 'pokalschiessen')
        ->assertJsonPath('data.contact.0.path', '/kontakt/kontaktformular-info');
});

it('returns a single category collection', function () {
    app(CategoryContentSeeder::class)->run();

    $response = $this->getJson('/api/category-content/service-materials');

    $response
        ->assertSuccessful()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.1.title', 'Beitrittserklärung')
        ->assertJsonPath('data.2.slug', 'links');
});
