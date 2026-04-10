<?php

namespace Database\Factories;

use App\Models\JacketListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JacketListing>
 */
class JacketListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(array_keys(JacketListing::typeOptions())),
            'title' => fake()->sentence(3),
            'details' => fake()->paragraph(),
            'contact_hint' => fake()->optional()->sentence(),
            'status' => 'published',
            'sort_order' => fake()->numberBetween(0, 50),
            'published_at' => now()->subMinutes(fake()->numberBetween(5, 1_440)),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (): array => [
            'status' => 'archived',
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);
    }
}
