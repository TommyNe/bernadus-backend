<?php

namespace Database\Factories;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NavigationItem>
 */
class NavigationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(asText: true, nb: 2);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'path' => '/'.Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'sort_order' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }
}
