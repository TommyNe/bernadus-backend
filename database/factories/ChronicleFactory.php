<?php

namespace Database\Factories;

use App\Models\Chronicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chronicle>
 */
class ChronicleFactory extends Factory
{
    protected $model = Chronicle::class;

    public function definition(): array
    {
        return [
            'chronicle_key' => fake()->unique()->slug(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
