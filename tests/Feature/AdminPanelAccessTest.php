<?php

use App\Filament\Widgets\ContentOverviewWidget;
use App\Filament\Widgets\RecentUpdatesWidget;
use App\Models\NavigationItem;
use App\Models\StartPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects guests to the admin login', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

it('forbids non admins from accessing the admin panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('renders the editorial dashboard and resources for admins', function () {
    $user = User::factory()->admin()->create();

    StartPage::factory()->create([
        'title' => 'Startseite Aktuell',
        'slug' => 'startseite-aktuell',
        'path' => '/startseite-aktuell',
    ]);

    NavigationItem::factory()->create([
        'title' => 'Kontakt',
        'slug' => 'kontakt',
        'path' => '/kontakt',
    ]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSeeText('Redaktion');

    $this->actingAs($user)
        ->get('/admin/navigation-items')
        ->assertSuccessful()
        ->assertSeeText('Navigation')
        ->assertSeeText('Kontakt');
});

it('renders the admin dashboard widgets with editorial data', function () {
    StartPage::factory()->create([
        'title' => 'Startseite Aktuell',
        'slug' => 'startseite-aktuell',
        'path' => '/startseite-aktuell',
    ]);

    NavigationItem::factory()->create([
        'title' => 'Kontakt',
        'slug' => 'kontakt',
        'path' => '/kontakt',
    ]);

    Livewire::test(ContentOverviewWidget::class)
        ->assertSeeText('Inhaltsübersicht')
        ->assertSeeText('Inhalte im System')
        ->assertSeeText('Navigationseinträge');

    Livewire::test(RecentUpdatesWidget::class)
        ->assertSeeText('Zuletzt bearbeitet')
        ->assertSeeText('Startseite Aktuell')
        ->assertSeeText('Kontakt');
});
