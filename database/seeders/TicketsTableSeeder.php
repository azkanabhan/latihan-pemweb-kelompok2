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
                'fk_event_id' => 1,
                'fk_attendee_id' => 1,
                'qr_code' => 'QR123ABC',
                'price' => 150000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fk_event_id' => 2,
                'fk_attendee_id' => 2,
                'qr_code' => 'QR456DEF',
                'price' => 250000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
