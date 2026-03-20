<?php

namespace Database\Factories;

use App\Models\Medium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medium>
 */
class MediumFactory extends Factory
{
    protected $model = Medium::class;

    public function definition(): array
    {
        return [
            'disk' => 'public',
            'path' => 'uploads/'.fake()->uuid().'.jpg',
            'filename' => fake()->slug().'.jpg',
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'size' => fake()->numberBetween(10_000, 500_000),
            'width' => 1600,
            'height' => 1200,
            'title' => fake()->optional()->sentence(3),
            'alt_text' => fake()->optional()->sentence(4),
        ];
    }
}
