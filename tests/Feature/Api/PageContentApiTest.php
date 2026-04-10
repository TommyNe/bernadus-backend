<?php

use App\Models\Flyer;
use App\Models\MembershipDocument;
use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('returns a composed headless payload for the membership page', function () {
    app(ContentFoundationSeeder::class)->run();

    MembershipDocument::factory()->create([
        'title' => 'Beitrittserklärung 2026',
        'description' => 'Aktuelles Formular für neue Mitglieder.',
        'pdf_path' => 'membership/application-2026.pdf',
        'original_filename' => 'beitrittserklaerung-2026.pdf',
    ]);

    Flyer::factory()->create([
        'title' => 'Flyer 2026',
        'pdf_path' => 'flyers/flyer-2026.pdf',
        'original_filename' => 'flyer-2026.pdf',
    ]);

    $this->getJson('/api/pages/mitglied-werden/content')
        ->assertSuccessful()
        ->assertJsonPath('data.slug', 'mitglied-werden')
        ->assertJsonPath('data.title', 'Mitglied werden')
        ->assertJsonPath('data.hero.title', 'Mitglied werden')
        ->assertJsonPath('data.hero.subtitle', 'Gemeinschaft vor Ort')
        ->assertJsonPath('data.intro', 'Die Originalseite bündelt hier praktische Informationen, Downloads und Kontaktwege für alle, die sich dem Verein anschließen möchten.')
        ->assertJsonPath('data.flyerUrl', Storage::disk('public')->url('flyers/flyer-2026.pdf'))
        ->assertJsonPath('data.applicationUrl', Storage::disk('public')->url('membership/application-2026.pdf'))
        ->assertJsonPath('data.contactUrl', 'https://www.schuetzenverein-tinnen.de/kontakt/')
        ->assertJsonPath('data.membershipOffers.0.title', 'Beitrittserklärung')
        ->assertJsonPath('data.membershipOffers.0.icon', null)
        ->assertJsonPath('data.membershipOffers.1.linkLabel', 'Flyer öffnen')
        ->assertJsonPath('data.practicalNotes.0', 'Für aktuelle Formulare bleibt die offizielle Quelle des Vereins maßgeblich.')
        ->assertJsonPath('data.faq.1.question', 'Gibt es hier offizielle Beitragssätze?')
        ->assertJsonPath('data.applicationDocument.type', 'application')
        ->assertJsonPath('data.applicationDocument.filename', 'beitrittserklaerung-2026.pdf')
        ->assertJsonPath('data.flyerDocument.type', 'flyer')
        ->assertJsonPath('data.flyerDocument.filename', 'flyer-2026.pdf')
        ->assertJsonCount(2, 'data.documents')
        ->assertJsonPath('data.meta.status', 'published');
});

it('returns not found for missing page content', function () {
    $this->getJson('/api/pages/unbekannt/content')->assertNotFound();
});
