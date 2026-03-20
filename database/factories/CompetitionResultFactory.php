<?php

namespace Database\Factories;

use App\Models\CompetitionResult;
use App\Models\CompetitionResultCategory;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionResult>
 */
class CompetitionResultFactory extends Factory
{
    protected $model = CompetitionResult::class;

    public function definition(): array
    {
        return [
            'competition_result_category_id' => CompetitionResultCategory::factory(),
            'person_id' => fake()->boolean(50) ? Person::factory() : null,
            'winner_name' => fake()->name(),
            'rank' => fake()->numberBetween(1, 3),
            'score' => fake()->randomFloat(2, 10, 250),
            'score_text' => fake()->optional()->numberBetween(10, 250).' Ringe',
        ];
    }
}
