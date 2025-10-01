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
                // Map attendee_id to attendees.attendee_id
                'attendee_id' => DB::table('attendees')
                    ->join('users', 'users.id', '=', 'attendees.user_id')
                    ->where('users.email', 'john@example.com')
                    ->value('attendees.id'),
                'event_id' => 1,
                'amount' => 150000,
                'method' => 'credit_card',
                'qr_code' => 'QR123ABC',
                'status' => 'active',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'attendee_id' => DB::table('attendees')
                    ->join('users', 'users.id', '=', 'attendees.user_id')
                    ->where('users.email', 'jane@example.com')
                    ->value('attendees.id'),
                'event_id' => 2,
                'amount' => 250000,
                'method' => 'paypal',
                'qr_code' => 'QR456DEF',
                'status' => 'active',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
