<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EventCreatorsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Align with migration: event_creators has only creator_id (PK), user_id (FK), age, timestamps
        $creator1Id = DB::table('users')->where('email', 'creator1@example.com')->value('id');
        $creator2Id = DB::table('users')->where('email', 'creator2@example.com')->value('id');

        if ($creator1Id) {
            DB::table('event_creators')->updateOrInsert(
                ['user_id' => $creator1Id],
                [
                    'user_id' => $creator1Id,
                    'age' => 30,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if ($creator2Id) {
            DB::table('event_creators')->updateOrInsert(
                ['user_id' => $creator2Id],
                [
                    'user_id' => $creator2Id,
                    'age' => 25,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
