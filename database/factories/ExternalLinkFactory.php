<?php

namespace Database\Factories;

use App\Models\ExternalLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExternalLink>
 */
class ExternalLinkFactory extends Factory
{
    protected $model = ExternalLink::class;

    public function definition(): array
    {
        return [
            'link_key' => fake()->unique()->slug(),
            'label' => fake()->sentence(2),
            'url' => fake()->url(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
