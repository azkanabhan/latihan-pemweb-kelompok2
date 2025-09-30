<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsRatingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews_ratings')->insert([
            [
                'user_id' => 1, // attendee
                'event_id' => 1,
                'body' => 'Great event, very insightful!',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'event_id' => 2,
                'body' => 'Amazing atmosphere and performances!',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
