<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        DB::table('payments')->insert([
            [
                'user_id' => 1,
                'amount' => 100.00,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'amount' => 50.00,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'amount' => 75.50,
                'status' => 'failed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}