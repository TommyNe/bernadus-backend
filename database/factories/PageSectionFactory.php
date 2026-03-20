<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageSection>
 */
class PageSectionFactory extends Factory
{
    protected $model = PageSection::class;

    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'section_key' => fake()->optional()->slug(),
            'section_type' => fake()->randomElement(['hero', 'rich_text', 'notice', 'faq', 'cards', 'cta']),
            'title' => fake()->optional()->sentence(3),
            'subtitle' => fake()->optional()->sentence(),
            'content' => fake()->optional()->paragraphs(2, true),
            'data' => ['items' => [fake()->word(), fake()->word()]],
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
