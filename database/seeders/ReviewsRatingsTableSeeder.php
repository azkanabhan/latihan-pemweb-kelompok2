<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsRatingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $attendeeIds = DB::table('attendees')->pluck('id')->take(2)->values();
        $eventIds = DB::table('events')->pluck('event_id')->take(2)->values();

        if ($attendeeIds->isEmpty() || $eventIds->isEmpty()) {
            return;
        }

        $now = now();

        $rows = [
            [
                'attendee_id' => $attendeeIds[0],
                'event_id' => $eventIds[0],
                'body' => 'Great event, learned a lot!',
                'rating' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        if ($attendeeIds->count() > 1 && $eventIds->count() > 1) {
            $rows[] = [
                'attendee_id' => $attendeeIds[1],
                'event_id' => $eventIds[1],
                'body' => 'Good music, could improve the sound system.',
                'rating' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($rows as $row) {
            DB::table('reviews_ratings')->updateOrInsert(
                [
                    'attendee_id' => $row['attendee_id'],
                    'event_id' => $row['event_id'],
                ],
                $row
            );
        }
    }
}


