<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Find two creators (use event_creators.id as referenced by events.events_creators_id)
        $creatorIds = DB::table('event_creators')->pluck('id')->take(2)->values();

        if ($creatorIds->isEmpty()) {
            return;
        }

        $now = now();

        // Seed a couple of events
        $rows = [];
        $rows[] = [
            'events_creators_id' => $creatorIds[0],
            'event_name' => 'Tech Conference 2025',
            'event_description' => 'Annual technology conference with talks and workshops.',
            'event_location' => 'Jakarta Convention Center',
            'event_date' => now()->addDays(30),
            'event_capacity' => 500,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if ($creatorIds->count() > 1) {
            $rows[] = [
                'events_creators_id' => $creatorIds[1],
                'event_name' => 'Music Fest',
                'event_description' => 'Outdoor music festival featuring local bands.',
                'event_location' => 'Bandung City Park',
                'event_date' => now()->addDays(45),
                'event_capacity' => 300,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($rows as $row) {
            DB::table('events')->updateOrInsert(
                [
                    'events_creators_id' => $row['events_creators_id'],
                    'event_name' => $row['event_name'],
                    'event_date' => $row['event_date'],
                ],
                $row
            );
        }
    }
}


