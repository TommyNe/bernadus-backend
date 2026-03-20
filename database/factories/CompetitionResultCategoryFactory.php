<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionResultCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionResultCategory>
 */
class CompetitionResultCategoryFactory extends Factory
{
    protected $model = CompetitionResultCategory::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'name' => fake()->randomElement(['Senioren', 'Damen', 'Junioren']),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
