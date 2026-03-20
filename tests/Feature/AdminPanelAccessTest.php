<?php

use App\Filament\Pages\ContentOverviewPage;
use App\Filament\Widgets\CurrentBoardWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\RecentChronicleEntriesWidget;
use App\Filament\Widgets\UpcomingEventsWidget;
use App\Models\Chronicle;
use App\Models\ChronicleEntry;
use App\Models\Event;
use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
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

    Page::factory()->create([
        'title' => 'Startseite',
        'slug' => 'startseite',
        'status' => 'published',
    ]);

    ExternalLink::factory()->create([
        'label' => 'Kontakt',
        'link_key' => 'official_contact',
    ]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSeeText('Redaktion');

    $this->actingAs($user)
        ->get('/admin/pages')
        ->assertSuccessful()
        ->assertSeeText('Seiten')
        ->assertSeeText('Startseite');

    $this->actingAs($user)
        ->get(ContentOverviewPage::getUrl(panel: 'admin'))
        ->assertSuccessful()
        ->assertSeeText('Inhaltsübersicht');
});

it('renders the admin dashboard widgets with editorial data', function () {
    Page::factory()->create([
        'title' => 'Startseite',
        'slug' => 'startseite',
        'status' => 'published',
    ]);

    $person = Person::factory()->create([
        'display_name' => 'Max Mustermann',
    ]);

    $role = Role::factory()->create([
        'name' => 'Vorsitzender',
        'role_key' => 'vorsitzender',
    ]);

    RoleAssignment::factory()->create([
        'person_id' => $person->id,
        'role_id' => $role->id,
        'is_current' => true,
    ]);

    Event::factory()->create([
        'title' => 'Schützenfest',
        'slug' => 'schuetzenfest',
        'starts_at' => now()->addWeek(),
    ]);

    $chronicle = Chronicle::factory()->create([
        'title' => 'Chronik der Schützenkönige',
        'chronicle_key' => 'shooting_kings',
    ]);

    ChronicleEntry::factory()->create([
        'chronicle_id' => $chronicle->id,
        'year' => 2025,
        'pair_text' => 'Anna & Paul',
    ]);

    ExternalLink::factory()->create([
        'label' => 'Flyer',
        'link_key' => 'official_flyer',
    ]);

    ExternalLink::factory()->create([
        'label' => 'Kontakt',
        'link_key' => 'official_contact',
    ]);

    ExternalLink::factory()->create([
        'label' => 'WhatsApp',
        'link_key' => 'official_whatsapp',
    ]);

    Livewire::test(DashboardStatsWidget::class)
        ->assertSeeText('Veröffentlichte Seiten')
        ->assertSeeText('Aktuelle Funktionäre')
        ->assertSeeText('Termine im laufenden Jahr');

    Livewire::test(CurrentBoardWidget::class)
        ->assertSeeText('Amt')
        ->assertSeeText('Max Mustermann')
        ->assertSeeText('Vorsitzender');

    Livewire::test(UpcomingEventsWidget::class)
        ->assertSeeText('Schützenfest');

    Livewire::test(RecentChronicleEntriesWidget::class)
        ->assertSeeText('Chronik der Schützenkönige')
        ->assertSeeText('Anna & Paul');

    Livewire::test(QuickLinksWidget::class)
        ->assertSeeText('Flyer')
        ->assertSeeText('Kontakt')
        ->assertSeeText('WhatsApp');
});
