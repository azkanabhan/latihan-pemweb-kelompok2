<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendeesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Align with migration: attendees has attendee_id (PK), user_id (FK), age, timestamps
        $johnId = DB::table('users')->where('email', 'john@example.com')->value('id');
        $janeId = DB::table('users')->where('email', 'jane@example.com')->value('id');

        if ($johnId) {
            DB::table('attendees')->updateOrInsert(
                ['user_id' => $johnId],
                [
                    'user_id' => $johnId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if ($janeId) {
            DB::table('attendees')->updateOrInsert(
                ['user_id' => $janeId],
                [
                    'user_id' => $janeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
