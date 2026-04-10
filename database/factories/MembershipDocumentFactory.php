<?php

namespace Database\Factories;

use App\Models\MembershipDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MembershipDocument>
 */
class MembershipDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'document_type' => 'application',
            'title' => 'Beitrittserklärung',
            'description' => fake()->optional()->sentence(),
            'pdf_path' => 'membership/'.fake()->uuid().'.pdf',
            'original_filename' => fake()->slug().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100_000, 3_000_000),
            'is_active' => true,
            'uploaded_at' => now()->subMinutes(fake()->numberBetween(5, 1_440)),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
