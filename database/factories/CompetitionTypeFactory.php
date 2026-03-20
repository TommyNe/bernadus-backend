<?php

namespace Database\Factories;

use App\Models\CompetitionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionType>
 */
class CompetitionTypeFactory extends Factory
{
    protected $model = CompetitionType::class;

    public function definition(): array
    {
        return [
            'type_key' => fake()->unique()->slug(),
            'name' => fake()->sentence(2),
        ];
    }
}
