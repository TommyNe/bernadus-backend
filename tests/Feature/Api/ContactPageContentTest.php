<?php

use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a structured headless payload for the contact page', function () {
    app(ContentFoundationSeeder::class)->run();

    $this->getJson('/api/pages/kontakt/content')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'kontakt')
        ->assertJsonPath('data.hero.eyebrow', 'Kontakt')
        ->assertJsonPath('data.hero.title', 'Direkt mit dem Verein in Verbindung treten')
        ->assertJsonPath('data.introTitle', 'Kontakt')
        ->assertJsonPath('data.contactCards.0.linkKey', 'official_contact')
        ->assertJsonPath('data.contactCards.0.url', 'https://www.schuetzenverein-tinnen.de/kontakt/')
        ->assertJsonPath('data.contactCards.1.linkKey', 'official_whatsapp')
        ->assertJsonPath('data.officialLinks.1.linkKey', 'official_home')
        ->assertJsonPath('data.address.title', 'Anschrift')
        ->assertJsonPath('data.address.city', 'Haren')
        ->assertJsonPath('data.address.lines.1', 'Brockloher Strasse 20')
        ->assertJsonPath('data.notes.0.title', 'Hinweis zur Quelle')
        ->assertJsonCount(2, 'data.contactCards')
        ->assertJsonCount(3, 'data.officialLinks')
        ->assertJsonCount(1, 'data.notes')
        ->assertJsonPath('data.meta.status', 'published');
});
