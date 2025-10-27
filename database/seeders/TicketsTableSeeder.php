<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get all events
        $eventIds = DB::table('events')->pluck('event_id')->values();

        if ($eventIds->isEmpty()) {
            return;
        }

        $now = now();
        $ticketTypes = [
            ['name' => 'Regular', 'price' => 100000],
            ['name' => 'VIP', 'price' => 200000],
            ['name' => 'Premium', 'price' => 350000],
        ];

        $rows = [];

        // Create tickets for each event
        foreach ($eventIds as $eventId) {
            // Randomly select 2-3 ticket types per event
            $selectedTypes = array_rand($ticketTypes, rand(2, min(3, count($ticketTypes))));
            
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }

            foreach ($selectedTypes as $index) {
                $type = $ticketTypes[$index];
                // Add some variation while ensuring price is never negative
                $variation = rand(-15, 25) * 10000;
                $finalPrice = max(50000, $type['price'] + $variation);
                
                $rows[] = [
                    'event_id' => $eventId,
                    'name' => $type['name'],
                    'price' => $finalPrice,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Insert all tickets
        foreach ($rows as $row) {
            DB::table('tickets')->updateOrInsert(
                [
                    'event_id' => $row['event_id'],
                    'name' => $row['name'],
                ],
                $row
            );
        }
    }
}
