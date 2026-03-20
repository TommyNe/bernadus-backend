<?php

namespace Database\Factories;

use App\Models\Medium;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'display_name' => fake()->name(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'portrait_media_id' => fake()->boolean(25) ? Medium::factory() : null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
