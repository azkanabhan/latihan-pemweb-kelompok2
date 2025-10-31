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
                'user_id' => DB::table('users')->where('email', 'john@example.com')->value('id'),
                'event_id' => 1,
                'ticket_id' => 1,
                'quantity' => 1,
                'amount' => 150000,
                'method' => 'credit_card',
                'status' => 'paid',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'attendee_id' => DB::table('attendees')
                    ->join('users', 'users.id', '=', 'attendees.user_id')
                    ->where('users.email', 'jane@example.com')
                    ->value('attendees.id'),
                'user_id' => DB::table('users')->where('email', 'jane@example.com')->value('id'),
                'event_id' => 2,
                'ticket_id' => 2,
                'quantity' => 1,
                'amount' => 250000,
                'method' => 'paypal',
                'status' => 'paid',
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
