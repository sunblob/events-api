<?php

namespace Database\Seeders;

use App\Models\EventYear;
use Illuminate\Database\Seeder;

class EventYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some predefined event years
        EventYear::create([
            'year' => 2024,
            'title' => 'Current Year Event',
            'description' => 'The main event for the current year',
            'is_active' => true,
        ]);

        EventYear::create([
            'year' => 2025,
            'title' => 'Upcoming Year Event',
            'description' => 'Planning for the next year event',
            'is_active' => true,
        ]);

        // Create some random event years
        EventYear::factory()->count(3)->create();
    }
} 