<?php

namespace Database\Seeders;

use App\Models\Chronicle;
use App\Models\CompetitionType;
use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\PageSection;
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

        foreach ($this->pageSections() as $slug => $sections) {
            $page = Page::query()->where('slug', $slug)->first();

            if ($page === null) {
                continue;
            }

            foreach ($sections as $section) {
                PageSection::query()->updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'section_key' => $section['section_key'],
                    ],
                    [
                        ...$section,
                        'page_id' => $page->id,
                    ],
                );
            }
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
            [
                'slug' => 'ueber-uns',
                'title' => 'Über uns',
                'nav_label' => 'Über uns',
                'meta_title' => 'Über uns | St. Bernadus Tinnen',
                'meta_description' => 'St. Bernadus Tinnen steht seit 1905 für gelebte Tradition, Gemeinschaft im Ort und verlässliches Vereinsleben über Generationen hinweg.',
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => 10,
            ],
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

    protected function pageSections(): array
    {
        return [
            'ueber-uns' => [
                [
                    'section_key' => 'hero-about',
                    'section_type' => 'hero',
                    'title' => 'Ein Verein mit Geschichte und Haltung',
                    'subtitle' => 'Über uns',
                    'content' => 'St. Bernadus Tinnen steht seit 1905 für gelebte Tradition, Gemeinschaft im Ort und verlässliches Vereinsleben über Generationen hinweg.',
                    'data' => [
                        'accent' => 'primary',
                    ],
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'about-overview',
                    'section_type' => 'cards',
                    'title' => 'Über unseren Verein',
                    'content' => 'Der offizielle Webauftritt des Schützenvereins Tinnen verbindet aktuelle Informationen mit Vereinschronik. Besonders stark dokumentiert sind Königspaare, Kinderkönige, Funktionäre und der Jahreskalender.',
                    'data' => [
                        'layout' => 'grid-4',
                        'items' => [
                            [
                                'title' => 'Chronik',
                                'content' => 'Mit Thron 2000 bis heute und Kinderkönig dokumentiert die Originalseite zentrale Teile der Vereinsgeschichte.',
                                'icon' => 'scroll-text',
                            ],
                            [
                                'title' => 'Funktionäre',
                                'content' => 'Die Vereinsseite nennt die aktuellen Funktionsträger und macht Zuständigkeiten im Vorstand sichtbar.',
                                'icon' => 'users',
                            ],
                            [
                                'title' => 'Termine',
                                'content' => 'Der Jahreskalender 2026 enthält Versammlungen, Schützenfest, Plakettenschießen und weitere Vereinstermine.',
                                'icon' => 'calendar-days',
                            ],
                            [
                                'title' => 'Service',
                                'content' => 'Flyer, WhatsApp-Newsletter und weitere praktische Hinweise ergänzen die eigentlichen Vereinsinhalte.',
                                'icon' => 'download',
                            ],
                        ],
                    ],
                    'sort_order' => 20,
                ],
                [
                    'section_key' => 'history-meaning',
                    'section_type' => 'rich_text',
                    'title' => 'Geschichte & Bedeutung',
                    'content' => "Seit 1905 ist der Verein Teil des Dorflebens in Tinnen. Die online zugängliche Chronik zeigt, wie eng Schützenfest, Königswürde und ehrenamtliche Organisation über Jahrzehnte miteinander verbunden sind.\n\nInhaltlich setzt die Originalseite klare Schwerpunkte: dokumentierte Thronfolgen, Kinderkönige, Funktionärslisten, Veranstaltungskalender und praktische Vereinsinformationen wie Flyer oder WhatsApp-Newsletter.",
                    'data' => [
                        'anchor' => 'history',
                    ],
                    'sort_order' => 30,
                ],
                [
                    'section_key' => 'about-highlights',
                    'section_type' => 'cards',
                    'title' => 'Schwerpunkte der Website',
                    'content' => 'Die wichtigsten Inhalte des Vereinsauftritts lassen sich in drei Themenblöcke gliedern.',
                    'data' => [
                        'layout' => 'grid-3',
                        'items' => [
                            [
                                'title' => 'Chronikseiten',
                                'content' => 'Die Vereinswebsite stellt mit "Thron 2000 bis heute" und "Kinderkönig" zwei gut gepflegte Chronikbereiche bereit.',
                            ],
                            [
                                'title' => 'Vereinsalltag',
                                'content' => 'Termine, Pokalschießen, Plakettenschießen, Schützenfest und Winterfest prägen den öffentlich sichtbaren Jahreslauf.',
                            ],
                            [
                                'title' => 'Offizielle Quelle',
                                'content' => 'Für PDFs, den vollständigen Kalender und die laufend gepflegten Detailseiten verweist diese Website auf den offiziellen Vereinsauftritt.',
                                'link_label' => 'Offizielle Website besuchen',
                                'link_url' => 'https://www.schuetzenverein-tinnen.de/',
                            ],
                        ],
                    ],
                    'sort_order' => 40,
                ],
            ],
            'mitglied-werden' => [
                [
                    'section_key' => 'membership-hero',
                    'section_type' => 'hero',
                    'title' => 'Mitglied werden',
                    'subtitle' => 'Gemeinschaft vor Ort',
                    'content' => 'Die Originalseite bündelt hier praktische Informationen, Downloads und Kontaktwege für alle, die sich dem Verein anschließen möchten.',
                    'data' => [
                        'accent' => 'primary',
                    ],
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'membership-offers',
                    'section_type' => 'cards',
                    'title' => 'Alles für den Einstieg',
                    'content' => 'Die wichtigsten Einstiegspunkte stehen kompakt an einer Stelle bereit.',
                    'data' => [
                        'layout' => 'grid-2',
                        'items' => [
                            [
                                'title' => 'Beitrittserklärung',
                                'content' => 'Eigene Seite oder Download zur Mitgliedschaft mit allen formalen Informationen.',
                                'link_label' => 'Zum Antrag',
                                'link_url' => 'https://www.bernadus.example/antrag',
                            ],
                            [
                                'title' => 'Flyer',
                                'content' => 'Kompakter Überblick über Verein, Termine und das, was neue Mitglieder erwartet.',
                                'link_label' => 'Flyer öffnen',
                                'link_url' => 'https://www.bernadus.example/flyer',
                            ],
                        ],
                    ],
                    'sort_order' => 20,
                ],
                [
                    'section_key' => 'practical-note-1',
                    'section_type' => 'notice',
                    'title' => 'Wichtiger Hinweis',
                    'content' => 'Für aktuelle Formulare bleibt die offizielle Quelle des Vereins maßgeblich.',
                    'data' => [
                        'tone' => 'info',
                    ],
                    'sort_order' => 30,
                ],
                [
                    'section_key' => 'practical-note-2',
                    'section_type' => 'notice',
                    'title' => 'Kontakt',
                    'content' => 'Kontaktanfragen können direkt auf die offizielle Vereinsseite oder an die dort genannten Ansprechpartner weitergeleitet werden.',
                    'data' => [
                        'tone' => 'info',
                    ],
                    'sort_order' => 40,
                ],
                [
                    'section_key' => 'membership-faq',
                    'section_type' => 'faq',
                    'title' => 'Häufige Fragen',
                    'content' => null,
                    'data' => [
                        'items' => [
                            [
                                'title' => 'Wo finde ich die Beitrittserklärung?',
                                'content' => 'Über den offiziellen Menüpunkt oder die verlinkte Formularseite des Vereins.',
                            ],
                            [
                                'title' => 'Gibt es hier offizielle Beitragssätze?',
                                'content' => 'Nur, wenn sie ausdrücklich in der Datenquelle gepflegt und veröffentlicht wurden.',
                            ],
                        ],
                    ],
                    'sort_order' => 50,
                ],
            ],
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
            ['link_key' => 'page.mitglied-werden.flyer', 'label' => 'Flyer Mitglied werden', 'url' => 'https://www.bernadus.example/flyer', 'description' => 'Flyer zur Mitgliedschaft'],
            ['link_key' => 'page.mitglied-werden.contact', 'label' => 'Kontakt Mitglied werden', 'url' => 'https://www.bernadus.example/kontakt', 'description' => 'Kontakt zur Mitgliedschaft'],
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
