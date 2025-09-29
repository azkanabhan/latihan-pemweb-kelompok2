<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('review_ratings')->insert([
            [
                'user_id' => 1,
                'event_id' => 1,
                'body' => 'Acara sangat bagus, panitia ramah dan tertib.',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'event_id' => 1,
                'body' => 'Event cukup menarik, hanya saja kurang tepat waktu.',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'event_id' => 2,
                'body' => 'Acara biasa saja, tidak terlalu berkesan.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'event_id' => 2,
                'body' => 'Kurang terorganisir, jadwal tidak jelas.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'event_id' => 3,
                'body' => 'Sangat mengecewakan, tidak sesuai ekspektasi.',
                'rating' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}