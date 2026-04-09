<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionType;
use App\Models\Event;
use App\Models\Person;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class PlaqueShootingSeeder extends Seeder
{
    public function run(): void
    {
        $competitionType = CompetitionType::query()->firstOrCreate(
            ['type_key' => 'plaque_shooting'],
            ['name' => 'Plakettenschießen']
        );

        foreach ($this->competitions() as $competitionData) {
            $competition = Competition::query()->updateOrCreate(
                [
                    'competition_type_id' => $competitionType->id,
                    'year' => $competitionData['year'],
                ],
                [
                    'event_id' => $competitionData['event_id'],
                    'title' => $competitionData['title'],
                    'description' => $competitionData['description'],
                    'source_url' => $competitionData['source_url'],
                    'sort_order' => $competitionData['sort_order'],
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );

            foreach ($competitionData['categories'] as $categoryIndex => $categoryData) {
                $category = $competition->resultCategories()->updateOrCreate(
                    ['name' => $categoryData['name']],
                    ['sort_order' => $categoryData['sort_order'] ?? $categoryIndex]
                );

                foreach ($categoryData['results'] as $resultData) {
                    $person = $this->resolvePerson($resultData['winner_name']);

                    $category->results()->updateOrCreate(
                        ['rank' => $resultData['rank']],
                        [
                            'person_id' => $person?->id,
                            'winner_name' => $resultData['winner_name'],
                            'score' => $resultData['score'] ?? null,
                            'score_text' => $resultData['score_text'] ?? null,
                        ]
                    );
                }
            }

            $milestoneAwardIds = [];

            foreach ($this->milestoneAwards() as $awardIndex => $awardData) {
                $award = $competition->milestoneAwards()->updateOrCreate(
                    ['threshold' => $awardData['threshold']],
                    [
                        'award' => $awardData['award'],
                        'sort_order' => $awardData['sort_order'] ?? (($awardIndex + 1) * 10),
                    ]
                );

                $milestoneAwardIds[] = $award->id;
            }

            $competition->milestoneAwards()
                ->whereNotIn('id', $milestoneAwardIds)
                ->delete();

            $scoreAwardIds = [];

            foreach ($this->scoreAwards() as $awardIndex => $awardData) {
                $award = $competition->scoreAwards()->updateOrCreate(
                    [
                        'age_group' => $awardData['age_group'],
                        'award' => $awardData['award'],
                    ],
                    [
                        'rings' => $awardData['rings'],
                        'sort_order' => $awardData['sort_order'] ?? (($awardIndex + 1) * 10),
                    ]
                );

                $scoreAwardIds[] = $award->id;
            }

            $competition->scoreAwards()
                ->whereNotIn('id', $scoreAwardIds)
                ->delete();
        }
    }

    protected function resolvePerson(string $displayName): ?Person
    {
        [$firstName, $lastName] = array_pad(explode(' ', $displayName, 2), 2, null);

        return Person::query()->firstOrCreate(
            ['display_name' => $displayName],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]
        );
    }

    protected function resolveEvent(
        string $slug,
        string $title,
        string $description,
        string $startsAt,
        int $sortOrder
    ): Event {
        $venue = Venue::query()->firstOrCreate(['name' => 'Schützenhalle']);

        return Event::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'venue_id' => $venue->id,
                'title' => $title,
                'description' => $description,
                'starts_at' => $startsAt,
                'ends_at' => date('Y-m-d H:i:s', strtotime($startsAt.' +3 hours')),
                'all_day' => false,
                'display_date_text' => date('d.m.Y H:i', strtotime($startsAt)).' Uhr',
                'month_label' => date('F', strtotime($startsAt)),
                'audience_text' => 'Mitglieder',
                'source_url' => 'https://example.com/'.$slug,
                'sort_order' => $sortOrder,
            ]
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function competitions(): array
    {
        return [
            [
                'year' => 2023,
                'title' => 'Plakettenschießen 2023',
                'description' => 'Demo-Ergebnisse für das Plakettenschießen im Backend.',
                'source_url' => 'https://example.com/plakettenschiessen-2023',
                'sort_order' => 110,
                'event_id' => $this->resolveEvent(
                    slug: 'plakettenschiessen-2023',
                    title: 'Plakettenschießen 2023',
                    description: 'Demo-Termin für das Plakettenschießen 2023.',
                    startsAt: '2023-06-09 19:00:00',
                    sortOrder: 410
                )->id,
                'categories' => [
                    [
                        'name' => 'Schützenklasse',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Markus Thiel', 'score' => 48.0, 'score_text' => '48 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Stefan Moormann', 'score' => 46.0, 'score_text' => '46 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jörg Bothe', 'score' => 45.0, 'score_text' => '45 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Altersklasse',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Heinz Klene', 'score' => 47.0, 'score_text' => '47 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Helmut Efken', 'score' => 45.0, 'score_text' => '45 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Bernd Otten', 'score' => 44.0, 'score_text' => '44 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2024,
                'title' => 'Plakettenschießen 2024',
                'description' => 'Demo-Ergebnisse für das Plakettenschießen im Backend.',
                'source_url' => 'https://example.com/plakettenschiessen-2024',
                'sort_order' => 120,
                'event_id' => $this->resolveEvent(
                    slug: 'plakettenschiessen-2024',
                    title: 'Plakettenschießen 2024',
                    description: 'Demo-Termin für das Plakettenschießen 2024.',
                    startsAt: '2024-06-14 19:00:00',
                    sortOrder: 420
                )->id,
                'categories' => [
                    [
                        'name' => 'Schützenklasse',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Stefan Moormann', 'score' => 49.0, 'score_text' => '49 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Markus Thiel', 'score' => 47.0, 'score_text' => '47 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jörg Bothe', 'score' => 45.0, 'score_text' => '45 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Altersklasse',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Werner Thole', 'score' => 48.0, 'score_text' => '48 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Heinz Klene', 'score' => 46.0, 'score_text' => '46 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Helmut Efken', 'score' => 44.0, 'score_text' => '44 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2025,
                'title' => 'Plakettenschießen 2025',
                'description' => 'Demo-Ergebnisse für das Plakettenschießen im Backend.',
                'source_url' => 'https://example.com/plakettenschiessen-2025',
                'sort_order' => 130,
                'event_id' => $this->resolveEvent(
                    slug: 'plakettenschiessen-2025',
                    title: 'Plakettenschießen 2025',
                    description: 'Demo-Termin für das Plakettenschießen 2025.',
                    startsAt: '2025-06-13 19:00:00',
                    sortOrder: 430
                )->id,
                'categories' => [
                    [
                        'name' => 'Schützenklasse',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Markus Thiel', 'score' => 50.0, 'score_text' => '50 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Stefan Moormann', 'score' => 48.0, 'score_text' => '48 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jörg Bothe', 'score' => 46.0, 'score_text' => '46 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Altersklasse',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Heinz Klene', 'score' => 49.0, 'score_text' => '49 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Werner Thole', 'score' => 47.0, 'score_text' => '47 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Helmut Efken', 'score' => 45.0, 'score_text' => '45 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2026,
                'title' => 'Plakettenschießen 2026',
                'description' => 'Demo-Ergebnisse für das Plakettenschießen im Backend.',
                'source_url' => 'https://example.com/plakettenschiessen-2026',
                'sort_order' => 140,
                'event_id' => $this->resolveEvent(
                    slug: 'plakettenschiessen-2026',
                    title: 'Plakettenschießen 2026',
                    description: 'Demo-Termin für das Plakettenschießen 2026.',
                    startsAt: '2026-06-12 19:00:00',
                    sortOrder: 440
                )->id,
                'categories' => [
                    [
                        'name' => 'Schützenklasse',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Stefan Moormann', 'score' => 50.0, 'score_text' => '50 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Markus Thiel', 'score' => 49.0, 'score_text' => '49 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jörg Bothe', 'score' => 47.0, 'score_text' => '47 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Altersklasse',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Werner Thole', 'score' => 48.0, 'score_text' => '48 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Heinz Klene', 'score' => 47.0, 'score_text' => '47 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Helmut Efken', 'score' => 46.0, 'score_text' => '46 Ringe'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array{threshold: string, award: string, sort_order?: int}>
     */
    protected function milestoneAwards(): array
    {
        return [
            ['threshold' => '1 Gold', 'award' => 'Schützenschnur Grün'],
            ['threshold' => '4 Gold', 'award' => 'Schützenschnur Silber'],
            ['threshold' => '7 Gold', 'award' => 'Schützenschnur Gold'],
            ['threshold' => '8 Gold', 'award' => 'Plakette mit Kranz'],
            ['threshold' => '11 Gold', 'award' => 'Eichenlaub Silber'],
            ['threshold' => '15 Gold', 'award' => 'Eichenlaub Gold'],
            ['threshold' => '20 Gold', 'award' => 'Plakette mit Schnur Silber'],
            ['threshold' => '25 Gold', 'award' => 'Bild mit Plakette'],
            ['threshold' => '30 Gold', 'award' => 'Plakette mit Scheibe Bronze'],
            ['threshold' => '35 Gold', 'award' => 'Plakette mit Scheibe Silber'],
            ['threshold' => '40 Gold', 'award' => 'Plakette mit Scheibe Gold'],
            ['threshold' => '50 Gold', 'award' => 'Wandteller mit Orden'],
            ['threshold' => '60 Gold', 'award' => 'Anhänger und Plakette auf Schnur Gold'],
            ['threshold' => '65 Gold', 'award' => 'Scheibe Adler und Anhänger'],
            ['threshold' => '75 Gold', 'award' => 'Plakette mit Eichenlaub, Anhänger als Orden und Wandbild aus Holz'],
            ['threshold' => '80 Gold', 'award' => 'Plakette als Orden'],
            ['threshold' => '85 Gold', 'award' => 'Plakette als Orden'],
            ['threshold' => '90 Gold', 'award' => 'Plakette als Orden'],
            ['threshold' => '95 Gold', 'award' => 'Plakette als Orden'],
            ['threshold' => '100 Gold', 'award' => 'Besondere Plakette als Orden und zum Beispiel kleine Standuhr'],
        ];
    }

    /**
     * @return array<int, array{age_group: string, rings: int, award: string, sort_order?: int}>
     */
    protected function scoreAwards(): array
    {
        return [
            ['age_group' => 'bis 55 Jahre', 'rings' => 48, 'award' => 'Gold'],
            ['age_group' => 'bis 55 Jahre', 'rings' => 43, 'award' => 'Silber'],
            ['age_group' => 'bis 55 Jahre', 'rings' => 38, 'award' => 'Bronze'],
            ['age_group' => 'ab 55 Jahre', 'rings' => 43, 'award' => 'Gold'],
            ['age_group' => 'ab 55 Jahre', 'rings' => 38, 'award' => 'Silber'],
            ['age_group' => 'ab 55 Jahre', 'rings' => 33, 'award' => 'Bronze'],
            ['age_group' => 'ab 70 Jahre', 'rings' => 38, 'award' => 'Gold'],
            ['age_group' => 'ab 70 Jahre', 'rings' => 33, 'award' => 'Silber'],
            ['age_group' => 'ab 70 Jahre', 'rings' => 28, 'award' => 'Bronze'],
        ];
    }
}
