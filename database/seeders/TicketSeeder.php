<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'qr_code'   => Str::uuid(),
                'price'     => 50000.00,
                'events_id' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'qr_code'   => Str::uuid(),
                'price'     => 75000.00,
                'events_id' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'qr_code'   => Str::uuid(),
                'price'     => 100000.00,
                'events_id' => 2,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}
