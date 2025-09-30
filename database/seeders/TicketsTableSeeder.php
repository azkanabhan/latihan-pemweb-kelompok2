<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'event_id' => 1,
                'name' => 'Silver',
                'price' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2,
                'name' => 'Gold',
                'price' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2,
                'name' => 'Premium',
                'price' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
