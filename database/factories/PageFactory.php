<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 9999),
            'title' => $title,
            'nav_label' => fake()->optional()->words(2, true),
            'meta_title' => fake()->optional()->sentence(4),
            'meta_description' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => now(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
