<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionType;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competition>
 */
class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        return [
            'competition_type_id' => CompetitionType::factory(),
            'event_id' => fake()->boolean(50) ? Event::factory() : null,
            'year' => fake()->numberBetween(2000, 2030),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'source_url' => fake()->optional()->url(),
            'sort_order' => fake()->numberBetween(0, 10),
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}
