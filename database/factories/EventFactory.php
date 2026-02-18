<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        $startDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(2, 8) . ' hours');

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title) . '-' . uniqid(),
            'description' => fake()->paragraphs(3, true),
            'city' => fake()->city(),
            'venue_name' => fake()->company() . ' ' . fake()->randomElement(['Hall', 'Center', 'Arena', 'Theater', 'Stadium']),
            'venue_address' => fake()->streetAddress(),
            'country' => 'Peru',
            'latitude' => null,
            'longitude' => null,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['draft', 'published']),
            'ticket_price' => fake()->randomFloat(2, 15, 250),
            'available_tickets' => fake()->numberBetween(50, 500),
            'image' => null,
            'event_image' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'draft']);
    }
}
