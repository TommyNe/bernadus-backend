<?php

namespace Database\Factories;

use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'image_path' => 'gallery/'.fake()->uuid().'.jpg',
            'alt_text' => fake()->optional()->sentence(4),
            'sort_order' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
