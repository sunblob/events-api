<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\EventYear;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some predefined pages for each event year
        EventYear::all()->each(function ($eventYear) {
            // Create main page
            Page::create([
                'title' => 'Main Page',
                'slug' => 'main',
                'content' => [
                    'blocks' => [
                        [
                            'type' => 'heading',
                            'content' => 'Welcome to ' . $eventYear->year . ' Event'
                        ],
                        [
                            'type' => 'paragraph',
                            'content' => 'This is the main page for the event. Here you will find all the important information about the event.'
                        ]
                    ]
                ],
                'event_year_id' => $eventYear->id,
            ]);

            // Create about page
            Page::create([
                'title' => 'About',
                'slug' => 'about',
                'content' => [
                    'blocks' => [
                        [
                            'type' => 'heading',
                            'content' => 'About the Event'
                        ],
                        [
                            'type' => 'paragraph',
                            'content' => 'Learn more about our event and its history.'
                        ]
                    ]
                ],
                'event_year_id' => $eventYear->id,
            ]);

            // Create some random pages
            Page::factory()->count(2)->create([
                'event_year_id' => $eventYear->id,
            ]);
        });
    }
} 