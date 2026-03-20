<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\PlaqueAwardRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlaqueAwardRule>
 */
class PlaqueAwardRuleFactory extends Factory
{
    protected $model = PlaqueAwardRule::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'rule_type' => fake()->randomElement(['ring_threshold', 'gold_milestone']),
            'age_from' => fake()->optional()->numberBetween(6, 18),
            'age_to' => fake()->optional()->numberBetween(19, 80),
            'required_score' => fake()->optional()->numberBetween(10, 50),
            'required_gold_count' => fake()->optional()->numberBetween(1, 100),
            'award_name' => fake()->sentence(2),
            'award_level' => fake()->optional()->randomElement(['Bronze', 'Silber', 'Gold']),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
