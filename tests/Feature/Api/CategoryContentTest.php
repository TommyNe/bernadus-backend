<?php

use Database\Seeders\CategoryContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns all category tables grouped in one response', function () {
    app(CategoryContentSeeder::class)->run();

    $response = $this->getJson('/api/category-content');

    $response
        ->assertSuccessful()
        ->assertJsonCount(5, 'data.general_pages')
        ->assertJsonCount(4, 'data.about')
        ->assertJsonCount(3, 'data.team')
        ->assertJsonCount(5, 'data.events')
        ->assertJsonCount(3, 'data.gallery')
        ->assertJsonCount(5, 'data.membership')
        ->assertJsonCount(3, 'data.contact')
        ->assertJsonPath('data.general_pages.2.path', '/newsletter/whatsapp-newsletter')
        ->assertJsonPath('data.about.1.title', 'Geschichte')
        ->assertJsonPath('data.team.0.title', 'Vorstand & Team')
        ->assertJsonPath('data.events.3.slug', 'plaketten-pokalschiessen')
        ->assertJsonPath('data.membership.4.slug', 'faq')
        ->assertJsonPath('data.contact.1.path', '/kontakt/kontaktformular');
});

it('returns a single category collection', function () {
    app(CategoryContentSeeder::class)->run();

    $response = $this->getJson('/api/category-content/team');

    $response
        ->assertSuccessful()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.1.title', 'Vorstand')
        ->assertJsonPath('data.2.slug', 'beauftragte');
});
