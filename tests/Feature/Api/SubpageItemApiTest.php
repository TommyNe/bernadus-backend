<?php

use App\Models\SubpageItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns active structured subpage items', function () {
    SubpageItem::factory()->create([
        'title' => 'Chronik 1905',
        'slug' => 'chronik-1905',
        'category' => 'history',
        'sort_order' => 2,
    ]);

    SubpageItem::factory()->create([
        'title' => 'Mitgliedschaft fuer Familien',
        'slug' => 'mitgliedschaft-fuer-familien',
        'category' => 'membership-fee',
        'sort_order' => 1,
    ]);

    SubpageItem::factory()->create([
        'title' => 'Versteckter FAQ Eintrag',
        'slug' => 'versteckter-faq-eintrag',
        'category' => 'membership-faq',
        'is_active' => false,
    ]);

    $this->getJson('/api/subpage-items')
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.category', 'history')
        ->assertJsonPath('data.1.category', 'membership-fee');
});

it('returns structured items filtered by category', function () {
    SubpageItem::factory()->create([
        'title' => 'WhatsApp Kanal',
        'slug' => 'whatsapp-kanal',
        'category' => 'newsletter-channel',
    ]);

    SubpageItem::factory()->create([
        'title' => 'Kontakt per Mail',
        'slug' => 'kontakt-per-mail',
        'category' => 'contact-option',
    ]);

    $this->getJson('/api/subpage-items/category/newsletter-channel')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'whatsapp-kanal')
        ->assertJsonPath('data.0.category_label', 'Newsletter');
});

it('returns a single structured subpage item by slug', function () {
    $item = SubpageItem::factory()->create([
        'title' => 'Fotoalbum Sommer',
        'slug' => 'fotoalbum-sommer',
        'category' => 'gallery-photo',
    ]);

    $this->getJson('/api/subpage-items/'.$item->slug)
        ->assertSuccessful()
        ->assertJsonPath('data.title', 'Fotoalbum Sommer')
        ->assertJsonPath('data.category_label', 'Fotos');
});
