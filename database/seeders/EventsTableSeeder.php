<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Use available creators (events reference event_creators.id)
        $creatorIds = DB::table('event_creators')->pluck('id')->values();

        if ($creatorIds->isEmpty()) {
            return;
        }

        $now = now();
        
        $cities = [
            'Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Denpasar', 'Medan', 'Semarang', 'Makassar', 'Malang', 'Bogor',
        ];
        $themes = [
            'Tech Summit', 'Startup Expo', 'Design Conference', 'Music Festival', 'Film Showcase',
            'Art Fair', 'Culinary Festival', 'Health Symposium', 'Education Forum', 'Finance Forum',
        ];

        $requestedCount = 10;
        $approvedCount = 10;

        $rows = [];

        // Requested events
        for ($i = 1; $i <= $requestedCount; $i++) {
            $creatorId = $creatorIds[($i - 1) % $creatorIds->count()];
            $title = $themes[($i - 1) % count($themes)] . ' ' . (2025 + intdiv($i, 4));
            $city = $cities[($i + 1) % count($cities)];
            $rows[] = [
                'events_creators_id' => $creatorId,
                'event_name' => $title,
                'event_description' => "An engaging {$title} featuring speakers and activities.",
                'event_location' => $city,
                'event_date' => now()->addDays(7 + $i),
                'event_capacity' => 100 + ($i * 10),
                'status' => 'requested',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Approved events
        for ($i = 1; $i <= $approvedCount; $i++) {
            $creatorId = $creatorIds[($i - 1) % $creatorIds->count()];
            $title = $themes[($i + 3) % count($themes)] . ' ' . (2025 + intdiv($i, 3));
            $city = $cities[($i + 4) % count($cities)];
            $rows[] = [
                'events_creators_id' => $creatorId,
                'event_name' => $title,
                'event_description' => "A curated {$title} with premium sessions and workshops.",
                'event_location' => $city,
                'event_date' => now()->addDays(14 + $i),
                'event_capacity' => 150 + ($i * 10),
                'status' => 'approved',
                'approved_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert while avoiding duplicates by unique tuple (creator, name, date)
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


