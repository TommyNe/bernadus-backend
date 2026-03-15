<?php

namespace Database\Factories;

use App\Models\AboutSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AboutSection>
 */
class AboutSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        $slug = Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999);

        return [
            'title' => Str::title($title),
            'slug' => $slug,
            'path' => '/ueber-uns/'.$slug,
            'summary' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }
}
