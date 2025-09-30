<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'attendee_id' => 1, // sesuaikan dengan id yang ada di AttendeesSeeder
                'amount' => 150000,
                'method' => 'credit_card',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'attendee_id' => 2, // sesuaikan juga dengan id attendee yang ada
                'amount' => 250000,
                'method' => 'paypal',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
