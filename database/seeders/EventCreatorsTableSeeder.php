<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCreatorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('event_creators')->insert([
            [
                'username' => 'creator1',
                'email' => 'creator1@example.com',
                'password' => bcrypt('password123'),
                'age' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'creator2',
                'email' => 'creator2@example.com',
                'password' => bcrypt('password456'),
                'age' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
