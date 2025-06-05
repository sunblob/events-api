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
            'content' => $this->generateCleanHtml(3),
            'event_year_id' => EventYear::factory(),
        ];
    }

    private function generateCleanHtml(): string
{
    return <<<HTML
<h2>{$this->faker->sentence(4)}</h2>
<p>{$this->faker->paragraph(4)}</p>
<ul>
  <li>{$this->faker->sentence()}</li>
  <li>{$this->faker->sentence()}</li>
  <li>{$this->faker->sentence()}</li>
</ul>
<p><strong>{$this->faker->sentence(2)}</strong></p>
HTML;
}
}