<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            EventCreatorsTableSeeder::class,
            AttendeesTableSeeder::class,
            EventsTableSeeder::class,
            TicketsTableSeeder::class,
            PaymentsTableSeeder::class,
            ReviewsRatingsTableSeeder::class,
            AttendeeEventTableSeeder::class,
        ]);
    }
}
