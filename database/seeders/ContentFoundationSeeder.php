<?php

namespace Database\Seeders;

use App\Models\Chronicle;
use App\Models\CompetitionType;
use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\Role;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class ContentFoundationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page,
            );
        }

        foreach ($this->links() as $link) {
            ExternalLink::query()->updateOrCreate(
                ['link_key' => $link['link_key']],
                $link,
            );
        }

        foreach ($this->roles() as $role) {
            Role::query()->updateOrCreate(
                ['role_key' => $role['role_key']],
                $role,
            );
        }

        foreach ($this->venues() as $venue) {
            Venue::query()->updateOrCreate(
                ['name' => $venue['name']],
                $venue,
            );
        }

        foreach ($this->chronicles() as $chronicle) {
            Chronicle::query()->updateOrCreate(
                ['chronicle_key' => $chronicle['chronicle_key']],
                $chronicle,
            );
        }

        foreach ($this->competitionTypes() as $competitionType) {
            CompetitionType::query()->updateOrCreate(
                ['type_key' => $competitionType['type_key']],
                $competitionType,
            );
        }
    }

    protected function pages(): array
    {
        return [
            ['slug' => 'start', 'title' => 'Start', 'nav_label' => 'Start', 'status' => 'published', 'published_at' => now(), 'sort_order' => 0],
            ['slug' => 'ueber-uns', 'title' => 'Über uns', 'nav_label' => 'Über uns', 'status' => 'published', 'published_at' => now(), 'sort_order' => 10],
            ['slug' => 'ueber-uns/geschichte', 'title' => 'Geschichte', 'nav_label' => 'Geschichte', 'status' => 'published', 'published_at' => now(), 'sort_order' => 20],
            ['slug' => 'vorstand-team', 'title' => 'Vorstand & Team', 'nav_label' => 'Vorstand & Team', 'status' => 'published', 'published_at' => now(), 'sort_order' => 30],
            ['slug' => 'veranstaltungen', 'title' => 'Veranstaltungen', 'nav_label' => 'Veranstaltungen', 'status' => 'published', 'published_at' => now(), 'sort_order' => 40],
            ['slug' => 'veranstaltungen/kalender', 'title' => 'Kalender', 'nav_label' => 'Kalender', 'status' => 'published', 'published_at' => now(), 'sort_order' => 50],
            ['slug' => 'veranstaltungen/trainings', 'title' => 'Trainings', 'nav_label' => 'Trainings', 'status' => 'published', 'published_at' => now(), 'sort_order' => 60],
            ['slug' => 'veranstaltungen/plaketten-pokalschiessen', 'title' => 'Plaketten- und Pokalschießen', 'nav_label' => 'Plaketten & Pokale', 'status' => 'published', 'published_at' => now(), 'sort_order' => 70],
            ['slug' => 'galerie', 'title' => 'Galerie', 'nav_label' => 'Galerie', 'status' => 'published', 'published_at' => now(), 'sort_order' => 80],
            ['slug' => 'mitglied-werden', 'title' => 'Mitglied werden', 'nav_label' => 'Mitglied werden', 'status' => 'published', 'published_at' => now(), 'sort_order' => 90],
            ['slug' => 'mitglied-werden/vorteile', 'title' => 'Vorteile', 'nav_label' => 'Vorteile', 'status' => 'published', 'published_at' => now(), 'sort_order' => 100],
            ['slug' => 'mitglied-werden/beitrag', 'title' => 'Beitrag', 'nav_label' => 'Beitrag', 'status' => 'published', 'published_at' => now(), 'sort_order' => 110],
            ['slug' => 'mitglied-werden/antrag', 'title' => 'Antrag', 'nav_label' => 'Antrag', 'status' => 'published', 'published_at' => now(), 'sort_order' => 120],
            ['slug' => 'mitglied-werden/faq', 'title' => 'FAQ', 'nav_label' => 'FAQ', 'status' => 'published', 'published_at' => now(), 'sort_order' => 130],
            ['slug' => 'kontakt', 'title' => 'Kontakt', 'nav_label' => 'Kontakt', 'status' => 'published', 'published_at' => now(), 'sort_order' => 140],
            ['slug' => 'newsletter', 'title' => 'Newsletter', 'nav_label' => 'Newsletter', 'status' => 'published', 'published_at' => now(), 'sort_order' => 150],
        ];
    }

    protected function links(): array
    {
        return [
            ['link_key' => 'official_home', 'label' => 'Offizielle Website', 'url' => 'https://www.bernadus.example', 'description' => 'Hauptauftritt des Vereins'],
            ['link_key' => 'official_history', 'label' => 'Offizielle Historie', 'url' => 'https://www.bernadus.example/geschichte', 'description' => null],
            ['link_key' => 'official_child_king', 'label' => 'Kinderkönige', 'url' => 'https://www.bernadus.example/kinderkoenige', 'description' => null],
            ['link_key' => 'official_functionaries', 'label' => 'Funktionäre', 'url' => 'https://www.bernadus.example/funktionaere', 'description' => null],
            ['link_key' => 'official_calendar', 'label' => 'Kalender', 'url' => 'https://www.bernadus.example/kalender', 'description' => null],
            ['link_key' => 'official_flyer', 'label' => 'Flyer', 'url' => 'https://www.bernadus.example/flyer', 'description' => null],
            ['link_key' => 'official_contact', 'label' => 'Kontakt', 'url' => 'https://www.bernadus.example/kontakt', 'description' => null],
            ['link_key' => 'official_whatsapp', 'label' => 'WhatsApp', 'url' => 'https://chat.whatsapp.com/example', 'description' => null],
            ['link_key' => 'official_trophy_shooting', 'label' => 'Pokalschießen', 'url' => 'https://www.bernadus.example/pokalschiessen', 'description' => null],
            ['link_key' => 'official_plaque_shooting', 'label' => 'Plakettenschießen', 'url' => 'https://www.bernadus.example/plakettenschiessen', 'description' => null],
        ];
    }

    protected function roles(): array
    {
        return [
            ['role_key' => 'vorsitzender', 'name' => 'Vorsitzender', 'description' => null, 'sort_order' => 10],
            ['role_key' => 'oberst', 'name' => 'Oberst', 'description' => null, 'sort_order' => 20],
            ['role_key' => 'oberstleutnant', 'name' => 'Oberstleutnant', 'description' => null, 'sort_order' => 30],
            ['role_key' => 'schriftfuehrer', 'name' => 'Schriftführer', 'description' => null, 'sort_order' => 40],
            ['role_key' => 'kassenfuehrer', 'name' => 'Kassenführer', 'description' => null, 'sort_order' => 50],
        ];
    }

    protected function venues(): array
    {
        return [
            ['name' => 'Robbers', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
            ['name' => 'Kirche', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
            ['name' => 'Schützenhalle', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
            ['name' => 'Schießstand', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
            ['name' => 'Schützenplatz', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
            ['name' => 'DJK Vereinsheim', 'street' => null, 'postal_code' => null, 'city' => null, 'notes' => null],
        ];
    }

    protected function chronicles(): array
    {
        return [
            ['chronicle_key' => 'shooting_kings', 'title' => 'Chronik der Schützenkönige', 'description' => null, 'sort_order' => 10],
            ['chronicle_key' => 'child_kings', 'title' => 'Chronik der Kinderkönige', 'description' => null, 'sort_order' => 20],
        ];
    }

    protected function competitionTypes(): array
    {
        return [
            ['type_key' => 'trophy_shooting', 'name' => 'Pokalschießen'],
            ['type_key' => 'plaque_shooting', 'name' => 'Plakettenschießen'],
        ];
    }
}
