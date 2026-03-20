<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoleAssignment>
 */
class RoleAssignmentFactory extends Factory
{
    protected $model = RoleAssignment::class;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'role_id' => Role::factory(),
            'label_override' => fake()->optional()->sentence(2),
            'started_on' => fake()->optional()->date(),
            'ended_on' => null,
            'is_current' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
