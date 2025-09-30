<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'user_id' => 1, // FK ke event_creators
                'event_name' => 'Tech Conference',
                'event_description' => 'A conference about the latest in tech.',
                'event_location' => 'Jakarta',
                'event_date' => now()->addDays(10),
                'event_capacity' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'event_name' => 'Music Festival',
                'event_description' => 'Enjoy live performances from various artists.',
                'event_location' => 'Bandung',
                'event_date' => now()->addDays(20),
                'event_capacity' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
