<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\EventYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => [
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'content' => fake()->paragraph()
                    ],
                    [
                        'type' => 'heading',
                        'content' => fake()->sentence()
                    ]
                ]
            ],
            'event_year_id' => EventYear::factory(),
        ];
    }
} 