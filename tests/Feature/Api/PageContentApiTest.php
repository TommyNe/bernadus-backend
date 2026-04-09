<?php

use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a composed headless payload for the membership page', function () {
    app(ContentFoundationSeeder::class)->run();

    $this->getJson('/api/pages/mitglied-werden/content')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'mitglied-werden')
        ->assertJsonPath('data.title', 'Mitglied werden')
        ->assertJsonPath('data.intro', 'Die Originalseite bündelt hier praktische Informationen, Downloads und Kontaktwege für alle, die sich dem Verein anschließen möchten.')
        ->assertJsonPath('data.flyerUrl', 'https://www.bernadus.example/flyer')
        ->assertJsonPath('data.contactUrl', 'https://www.bernadus.example/kontakt')
        ->assertJsonPath('data.membershipOffers.0.title', 'Beitrittserklärung')
        ->assertJsonPath('data.membershipOffers.1.linkLabel', 'Flyer öffnen')
        ->assertJsonPath('data.practicalNotes.0', 'Für aktuelle Formulare bleibt die offizielle Quelle des Vereins maßgeblich.')
        ->assertJsonPath('data.faq.1.question', 'Gibt es hier offizielle Beitragssätze?')
        ->assertJsonPath('data.meta.status', 'published');
});

it('returns not found for missing page content', function () {
    $this->getJson('/api/pages/unbekannt/content')->assertNotFound();
});
