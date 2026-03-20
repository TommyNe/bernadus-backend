<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);
        $startsAt = now()->addDays(fake()->numberBetween(1, 60));

        return [
            'venue_id' => fake()->boolean(75) ? Venue::factory() : null,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 9999),
            'title' => $title,
            'description' => fake()->optional()->sentence(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->addHours(3),
            'all_day' => false,
            'display_date_text' => $startsAt->format('d.m.Y H:i'),
            'month_label' => $startsAt->translatedFormat('F'),
            'audience_text' => fake()->optional()->randomElement(['Alle', 'Mitglieder', 'Jugend']),
            'source_url' => fake()->optional()->url(),
            'external_ics_url' => fake()->optional()->url(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
