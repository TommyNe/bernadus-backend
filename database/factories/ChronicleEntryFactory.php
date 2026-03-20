<?php

namespace Database\Factories;

use App\Models\Chronicle;
use App\Models\ChronicleEntry;
use App\Models\Medium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChronicleEntry>
 */
class ChronicleEntryFactory extends Factory
{
    protected $model = ChronicleEntry::class;

    public function definition(): array
    {
        return [
            'chronicle_id' => Chronicle::factory(),
            'year' => fake()->numberBetween(1950, 2030),
            'title' => fake()->optional()->sentence(2),
            'headline' => fake()->optional()->sentence(3),
            'pair_text' => fake()->name().' & '.fake()->name(),
            'secondary_text' => fake()->optional()->sentence(),
            'image_media_id' => fake()->boolean(25) ? Medium::factory() : null,
            'external_image_url' => fake()->optional()->url(),
            'source_url' => fake()->optional()->url(),
            'is_highlighted' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
