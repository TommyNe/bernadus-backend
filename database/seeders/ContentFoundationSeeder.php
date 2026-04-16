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
            ['slug' => 'veranstaltungen/trainings', 'title' => 'Trainings', 'nav_label' => 'Trainings', 'status' => 'published', 'published_at' => now(), 'sort_order' => 60],
            ['slug' => 'veranstaltungen/plaketten-pokalschiessen', 'title' => 'Plaketten- und Pokalschießen', 'nav_label' => 'Plaketten & Pokale', 'status' => 'published', 'published_at' => now(), 'sort_order' => 70],
            ['slug' => 'galerie', 'title' => 'Galerie', 'nav_label' => 'Galerie', 'status' => 'published', 'published_at' => now(), 'sort_order' => 80],
            ['slug' => 'infos', 'title' => 'Infos', 'nav_label' => 'Infos', 'status' => 'published', 'published_at' => now(), 'sort_order' => 90],
            ['slug' => 'infos/flyer', 'title' => 'Flyer', 'nav_label' => 'Flyer', 'status' => 'published', 'published_at' => now(), 'sort_order' => 100],
            ['slug' => 'infos/jackenboerse', 'title' => 'Jackenbörse', 'nav_label' => 'Jackenbörse', 'status' => 'published', 'published_at' => now(), 'sort_order' => 110],
            ['slug' => 'infos/faq', 'title' => 'FAQ', 'nav_label' => 'FAQ', 'status' => 'published', 'published_at' => now(), 'sort_order' => 130],
            ['slug' => 'kontakt', 'title' => 'Kontakt', 'nav_label' => 'Kontakt', 'status' => 'published', 'published_at' => now(), 'sort_order' => 140],
            ['slug' => 'newsletter', 'title' => 'Newsletter', 'nav_label' => 'Newsletter', 'status' => 'published', 'published_at' => now(), 'sort_order' => 150],
            [
                'slug' => 'impressum',
                'title' => 'Impressum',
                'nav_label' => 'Impressum',
                'meta_title' => 'Impressum | St. Bernadus Tinnen',
                'meta_description' => 'Rechtliche Angaben, Kontaktinformationen und Anbieterkennzeichnung des Vereins.',
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => 160,
            ],
            [
                'slug' => 'datenschutz',
                'title' => 'Datenschutz',
                'nav_label' => 'Datenschutz',
                'meta_title' => 'Datenschutz | St. Bernadus Tinnen',
                'meta_description' => 'Datenschutzhinweise, Informationen zur Datenverarbeitung und Kontakt für Datenschutzanfragen.',
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => 170,
            ],
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
            'infos' => [
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
            'kontakt' => [
                [
                    'section_key' => 'contact-hero',
                    'section_type' => 'hero',
                    'title' => 'Direkt mit dem Verein in Verbindung treten',
                    'subtitle' => null,
                    'content' => 'Ob allgemeine Anfrage, Interesse an einer Mitgliedschaft oder organisatorische Rueckfrage: hier finden Sie die passenden Kontaktwege.',
                    'data' => [
                        'eyebrow' => 'Kontakt',
                    ],
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'contact-intro',
                    'section_type' => 'rich_text',
                    'title' => 'Kontakt',
                    'subtitle' => null,
                    'content' => 'Die offizielle Vereinsseite veroeffentlicht die Postanschrift und verweist fuer Rueckfragen auf die passenden Kontaktbereiche.',
                    'data' => null,
                    'sort_order' => 20,
                ],
                [
                    'section_key' => 'contact-options',
                    'section_type' => 'cards',
                    'title' => 'Kontaktwege',
                    'subtitle' => null,
                    'content' => 'Die folgenden Karten koennen im Backend erweitert, ein- und ausgeblendet oder neu sortiert werden.',
                    'data' => [
                        'layout' => 'grid-2',
                        'items' => [
                            [
                                'title' => 'Offizielle Kontaktseite',
                                'content' => 'Die oeffentlich sichtbaren Kontaktinformationen und aktuelle Hinweise werden direkt ueber die offizielle Vereinsseite gepflegt.',
                                'link_key' => 'official_contact',
                                'link_label' => 'Offizielle Kontaktseite oeffnen',
                                'sort_order' => 10,
                                'is_active' => true,
                            ],
                            [
                                'title' => 'WhatsApp-Newsletter',
                                'content' => 'Aktuelle Vereinsinformationen koennen zusaetzlich ueber den veroeffentlichten WhatsApp-Newsletter abgerufen werden.',
                                'link_key' => 'official_whatsapp',
                                'link_label' => 'Zum WhatsApp-Newsletter',
                                'sort_order' => 20,
                                'is_active' => true,
                            ],
                        ],
                    ],
                    'sort_order' => 30,
                ],
                [
                    'section_key' => 'contact-address',
                    'section_type' => 'rich_text',
                    'title' => 'Anschrift',
                    'subtitle' => null,
                    'content' => 'Postalische Anfragen koennen an die folgende Vereinsadresse gesendet werden.',
                    'data' => [
                        'name' => 'Schuetzenverein Tinnen',
                        'street' => 'Brockloher Strasse 20',
                        'postal_code' => '49733',
                        'city' => 'Haren',
                        'country' => null,
                        'address_notes' => 'Fuer direkte Rueckfragen bleibt die offizielle Kontaktseite die massgebliche Quelle.',
                    ],
                    'sort_order' => 40,
                ],
                [
                    'section_key' => 'contact-source-note',
                    'section_type' => 'notice',
                    'title' => 'Hinweis zur Quelle',
                    'subtitle' => null,
                    'content' => 'Die Inhalte dieser Seite wurden aus dem offiziellen Auftritt des Schuetzenvereins Tinnen uebernommen und redaktionell in die vorhandene Website-Struktur eingeordnet.',
                    'data' => [
                        'tone' => 'info',
                    ],
                    'sort_order' => 50,
                ],
                [
                    'section_key' => 'contact-official-links',
                    'section_type' => 'cards',
                    'title' => 'Offizielle Links',
                    'subtitle' => null,
                    'content' => 'Externe Ziele werden zentral ueber die Linkverwaltung gepflegt und koennen hier referenziert werden.',
                    'data' => [
                        'layout' => 'grid-3',
                        'items' => [
                            [
                                'title' => 'Kontaktseite',
                                'content' => 'Direkter Weg zur offiziellen Kontaktseite des Vereins.',
                                'link_key' => 'official_contact',
                                'link_label' => 'Oeffnen',
                                'sort_order' => 10,
                                'is_active' => true,
                            ],
                            [
                                'title' => 'Offizielle Website',
                                'content' => 'Weitere veroeffentlichte Informationen und Verweise des Vereins.',
                                'link_key' => 'official_home',
                                'link_label' => 'Website oeffnen',
                                'sort_order' => 20,
                                'is_active' => true,
                            ],
                            [
                                'title' => 'WhatsApp',
                                'content' => 'Offizieller WhatsApp-Newsletter des Vereins.',
                                'link_key' => 'official_whatsapp',
                                'link_label' => 'Zum Kanal',
                                'sort_order' => 30,
                                'is_active' => true,
                            ],
                        ],
                    ],
                    'sort_order' => 60,
                ],
            ],
            'veranstaltungen/plaketten-pokalschiessen' => [
                [
                    'section_key' => 'plaque_rules',
                    'section_type' => 'rich_text',
                    'title' => 'Plakettenschießen',
                    'subtitle' => 'Regeln & Auszeichnungen',
                    'content' => "Beim Plakettenschießen entscheiden Serien, Ringzahlen und Altersklassen über die erreichten Auszeichnungen.\nDie aktuellen Regelwerke und Ergebnislisten werden für diese Seite direkt aus dem Backend geladen.",
                    'data' => null,
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'plaque_results_intro',
                    'section_type' => 'rich_text',
                    'title' => 'Aktuelle Ergebnislisten',
                    'subtitle' => null,
                    'content' => "Die Ergebnisübersichten zeigen die jeweils gepflegten Wettbewerbsjahre mit Klassen und Platzierungen.\nSobald neue Wettbewerbe im Backend eingetragen sind, erscheinen sie automatisch an dieser Stelle.",
                    'data' => null,
                    'sort_order' => 20,
                ],
                [
                    'section_key' => 'trophy_shooting',
                    'section_type' => 'rich_text',
                    'title' => 'Pokalschießen',
                    'subtitle' => 'Wettbewerb & Ergebnisse',
                    'content' => "Das Pokalschießen wird als eigener Wettbewerb mit Kategorien und Platzierungen gepflegt.\nDie Karten unten zeigen die aktuell im Backend hinterlegten Ergebnisjahre.",
                    'data' => null,
                    'sort_order' => 30,
                ],
                [
                    'section_key' => 'trophy_results_intro',
                    'section_type' => 'rich_text',
                    'title' => 'Pflege im Backend',
                    'subtitle' => null,
                    'content' => "Ergebnisse, Kategorien und Termine lassen sich zentral im Laravel-Backend pflegen.\nDadurch bleibt die Veranstaltungsseite ohne statische Listen aktuell.",
                    'data' => null,
                    'sort_order' => 40,
                ],
            ],
            'impressum' => [
                [
                    'section_key' => 'legal-notice-content',
                    'section_type' => 'rich_text',
                    'title' => 'Angaben gemäß § 5 TMG',
                    'subtitle' => null,
                    'content' => "St. Bernadus Tinnen e. V.\nMusterstraße 1\n49700 Tinnen\n\nVertreten durch den Vorstand des Vereins.\nKontakt: info@bernadus.example\n\nDie konkreten Vereins- und Ansprechpartnerdaten können im Backend aktualisiert werden.",
                    'data' => null,
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'legal-notice-contact',
                    'section_type' => 'notice',
                    'title' => 'Hinweis zur Pflege',
                    'subtitle' => null,
                    'content' => 'Diese Angaben werden zentral im Backend gepflegt und können dort rechtssicher aktualisiert werden.',
                    'data' => [
                        'tone' => 'info',
                    ],
                    'sort_order' => 20,
                ],
            ],
            'datenschutz' => [
                [
                    'section_key' => 'privacy-content',
                    'section_type' => 'rich_text',
                    'title' => 'Datenschutzhinweise',
                    'subtitle' => null,
                    'content' => "Diese Website verarbeitet personenbezogene Daten nur im notwendigen Umfang.\n\nDie konkreten Hinweise zu Verantwortlichkeit, Rechtsgrundlagen, Speicherfristen und Betroffenenrechten können im Backend gepflegt und aktualisiert werden.\n\nFür Datenschutzanfragen kann eine zentrale Kontaktadresse im Rechtstext hinterlegt werden.",
                    'data' => null,
                    'sort_order' => 10,
                ],
                [
                    'section_key' => 'privacy-note',
                    'section_type' => 'notice',
                    'title' => 'Redaktioneller Hinweis',
                    'subtitle' => null,
                    'content' => 'Die vollständige Datenschutzerklärung sollte regelmäßig rechtlich geprüft und im Backend aktualisiert werden.',
                    'data' => [
                        'tone' => 'warning',
                    ],
                    'sort_order' => 20,
                ],
            ],
        ];
    }

    protected function links(): array
    {
        return [
            ['link_key' => 'official_home', 'label' => 'Offizielle Website', 'url' => 'https://www.schuetzenverein-tinnen.de/', 'description' => 'Hauptauftritt des Vereins'],
            ['link_key' => 'official_history', 'label' => 'Offizielle Historie', 'url' => 'https://www.schuetzenverein-tinnen.de/thron-2000-bis-heute/', 'description' => null],
            ['link_key' => 'official_child_king', 'label' => 'Kinderkönige', 'url' => 'https://www.schuetzenverein-tinnen.de/kinderk%C3%B6nig/', 'description' => null],
            ['link_key' => 'official_functionaries', 'label' => 'Funktionäre', 'url' => 'https://www.schuetzenverein-tinnen.de/funktion%C3%A4re/', 'description' => null],
            ['link_key' => 'official_calendar', 'label' => 'Kalender', 'url' => 'https://www.schuetzenverein-tinnen.de/termine/', 'description' => null],
            ['link_key' => 'official_flyer', 'label' => 'Flyer', 'url' => 'https://www.schuetzenverein-tinnen.de/flyer/', 'description' => null],
            ['link_key' => 'official_contact', 'label' => 'Kontakt', 'url' => 'https://www.schuetzenverein-tinnen.de/kontakt/', 'description' => null],
            ['link_key' => 'page.mitglied-werden.flyer', 'label' => 'Flyer Mitglied werden', 'url' => 'https://www.schuetzenverein-tinnen.de/flyer/', 'description' => 'Flyer zur Mitgliedschaft'],
            ['link_key' => 'page.mitglied-werden.contact', 'label' => 'Kontakt Mitglied werden', 'url' => 'https://www.schuetzenverein-tinnen.de/kontakt/', 'description' => 'Kontakt zur Mitgliedschaft'],
            ['link_key' => 'official_whatsapp', 'label' => 'WhatsApp', 'url' => 'https://www.schuetzenverein-tinnen.de/whatsapp-newsletter/', 'description' => null],
            ['link_key' => 'official_trophy_shooting', 'label' => 'Pokalschießen', 'url' => 'https://www.schuetzenverein-tinnen.de/pokalschie%C3%9Fen/', 'description' => null],
            ['link_key' => 'official_plaque_shooting', 'label' => 'Plakettenschießen', 'url' => 'https://www.schuetzenverein-tinnen.de/plakettenschie%C3%9Fen/', 'description' => null],
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
