<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionType;
use App\Models\Event;
use App\Models\Person;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class TrophyShootingSeeder extends Seeder
{
    public function run(): void
    {
        $competitionType = CompetitionType::query()->firstOrCreate(
            ['type_key' => 'trophy_shooting'],
            ['name' => 'Pokalschießen']
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
        $venue = Venue::query()->firstOrCreate(['name' => 'Schießstand']);

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
                'title' => 'Pokalschießen 2023',
                'description' => 'Demo-Ergebnisse für die Pflege und Darstellung im Backend.',
                'source_url' => 'https://example.com/pokalschiessen-2023',
                'sort_order' => 10,
                'event_id' => $this->resolveEvent(
                    slug: 'pokalschiessen-2023',
                    title: 'Pokalschießen 2023',
                    description: 'Demo-Termin für das Pokalschießen 2023.',
                    startsAt: '2023-05-12 18:30:00',
                    sortOrder: 310
                )->id,
                'categories' => [
                    [
                        'name' => 'Senioren',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Heinz Klene', 'score' => 96.5, 'score_text' => '96,5 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Helmut Efken', 'score' => 94.0, 'score_text' => '94,0 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Bernd Otten', 'score' => 92.5, 'score_text' => '92,5 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Damen',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Anna Becker', 'score' => 95.0, 'score_text' => '95,0 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Maria Schulte', 'score' => 93.5, 'score_text' => '93,5 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Petra Feldmann', 'score' => 91.0, 'score_text' => '91,0 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Junioren',
                        'sort_order' => 30,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Lukas Wessels', 'score' => 90.5, 'score_text' => '90,5 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Jan Hagemann', 'score' => 88.0, 'score_text' => '88,0 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Nils Kramer', 'score' => 86.5, 'score_text' => '86,5 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2024,
                'title' => 'Pokalschießen 2024',
                'description' => 'Demo-Ergebnisse für die Pflege und Darstellung im Backend.',
                'source_url' => 'https://example.com/pokalschiessen-2024',
                'sort_order' => 20,
                'event_id' => $this->resolveEvent(
                    slug: 'pokalschiessen-2024',
                    title: 'Pokalschießen 2024',
                    description: 'Demo-Termin für das Pokalschießen 2024.',
                    startsAt: '2024-05-17 18:30:00',
                    sortOrder: 320
                )->id,
                'categories' => [
                    [
                        'name' => 'Senioren',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Werner Thole', 'score' => 97.0, 'score_text' => '97,0 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Heinz Klene', 'score' => 95.0, 'score_text' => '95,0 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Bernd Otten', 'score' => 93.0, 'score_text' => '93,0 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Damen',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Kathrin Meyer', 'score' => 94.5, 'score_text' => '94,5 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Anna Becker', 'score' => 92.5, 'score_text' => '92,5 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Maren Lübbers', 'score' => 90.0, 'score_text' => '90,0 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Junioren',
                        'sort_order' => 30,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Tom Gerdes', 'score' => 91.5, 'score_text' => '91,5 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Lukas Wessels', 'score' => 89.0, 'score_text' => '89,0 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jan Hagemann', 'score' => 87.5, 'score_text' => '87,5 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2025,
                'title' => 'Pokalschießen 2025',
                'description' => 'Demo-Ergebnisse für die Pflege und Darstellung im Backend.',
                'source_url' => 'https://example.com/pokalschiessen-2025',
                'sort_order' => 30,
                'event_id' => $this->resolveEvent(
                    slug: 'pokalschiessen-2025',
                    title: 'Pokalschießen 2025',
                    description: 'Demo-Termin für das Pokalschießen 2025.',
                    startsAt: '2025-05-16 18:30:00',
                    sortOrder: 330
                )->id,
                'categories' => [
                    [
                        'name' => 'Senioren',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Heinz Klene', 'score' => 96.4, 'score_text' => '96,4 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Helmut Efken', 'score' => 94.8, 'score_text' => '94,8 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Bernd Otten', 'score' => 92.1, 'score_text' => '92,1 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Damen',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Anna Becker', 'score' => 95.3, 'score_text' => '95,3 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Maria Schulte', 'score' => 93.1, 'score_text' => '93,1 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Petra Feldmann', 'score' => 90.4, 'score_text' => '90,4 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Junioren',
                        'sort_order' => 30,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Lukas Wessels', 'score' => 91.7, 'score_text' => '91,7 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Jan Hagemann', 'score' => 89.3, 'score_text' => '89,3 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Nils Kramer', 'score' => 87.6, 'score_text' => '87,6 Ringe'],
                        ],
                    ],
                ],
            ],
            [
                'year' => 2026,
                'title' => 'Pokalschießen 2026',
                'description' => 'Demo-Ergebnisse für die Pflege und Darstellung im Backend.',
                'source_url' => 'https://example.com/pokalschiessen-2026',
                'sort_order' => 40,
                'event_id' => $this->resolveEvent(
                    slug: 'pokalschiessen-2026',
                    title: 'Pokalschießen 2026',
                    description: 'Demo-Termin für das Pokalschießen 2026.',
                    startsAt: '2026-05-15 18:30:00',
                    sortOrder: 340
                )->id,
                'categories' => [
                    [
                        'name' => 'Senioren',
                        'sort_order' => 10,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Werner Thole', 'score' => 97.2, 'score_text' => '97,2 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Heinz Klene', 'score' => 95.7, 'score_text' => '95,7 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Helmut Efken', 'score' => 94.2, 'score_text' => '94,2 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Damen',
                        'sort_order' => 20,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Kathrin Meyer', 'score' => 95.8, 'score_text' => '95,8 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Anna Becker', 'score' => 93.7, 'score_text' => '93,7 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Maren Lübbers', 'score' => 91.9, 'score_text' => '91,9 Ringe'],
                        ],
                    ],
                    [
                        'name' => 'Junioren',
                        'sort_order' => 30,
                        'results' => [
                            ['rank' => 1, 'winner_name' => 'Tom Gerdes', 'score' => 92.4, 'score_text' => '92,4 Ringe'],
                            ['rank' => 2, 'winner_name' => 'Lukas Wessels', 'score' => 90.2, 'score_text' => '90,2 Ringe'],
                            ['rank' => 3, 'winner_name' => 'Jan Hagemann', 'score' => 88.8, 'score_text' => '88,8 Ringe'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
