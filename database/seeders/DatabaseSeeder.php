<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EventCreatorsTableSeeder::class,
            EventsTableSeeder::class,
            AttendeesTableSeeder::class,
            TicketsTableSeeder::class,
            PaymentsTableSeeder::class,
            ReviewsRatingsTableSeeder::class,
        ]);
    }
}
