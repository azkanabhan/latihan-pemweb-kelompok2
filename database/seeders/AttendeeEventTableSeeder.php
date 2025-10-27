<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendeeEventTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID dari attendees berdasarkan email user (contoh dari user seeder)
        $john = DB::table('attendees')
            ->join('users', 'users.id', '=', 'attendees.user_id')
            ->where('users.email', 'john@example.com')
            ->value('attendees.id');

        $jane = DB::table('attendees')
            ->join('users', 'users.id', '=', 'attendees.user_id')
            ->where('users.email', 'jane@example.com')
            ->value('attendees.id');

        // Masukkan hubungan antara attendee dan event
        DB::table('attendee_event')->insert([
            [
                'attendee_id' => $john,
                'event_id' => 1,
            ],
            [
                'attendee_id' => $jane,
                'event_id' => 2,
            ],
        ]);
    }
}
