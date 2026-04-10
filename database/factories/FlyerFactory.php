<?php

namespace Database\Factories;

use App\Models\Flyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flyer>
 */
class FlyerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->optional()->sentence(3),
            'pdf_path' => 'flyers/'.fake()->uuid().'.pdf',
            'original_filename' => fake()->slug().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100_000, 5_000_000),
            'is_active' => true,
            'uploaded_at' => now()->subMinutes(fake()->numberBetween(5, 1_440)),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
