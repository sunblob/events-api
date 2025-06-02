<?php

namespace Database\Factories;

use App\Models\EventYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventYear>
 */
class EventYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->unique()->numberBetween(2015, 2025);
        return [
            'year' => $year,
            'title' => fake()->optional(0.8)->sentence(3),
            'description' => fake()->optional(0.7)->paragraph(),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }
}
