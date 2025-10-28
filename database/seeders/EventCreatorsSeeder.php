<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EventCreatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_creators')->insert([
            [
                'user_id' => 1, // Relasi ke users table
                'email' => 'creator1@example.com',
                'username' => 'creator_one',
                'password' => Hash::make('password'),
                'age' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2, // Relasi ke users table
                'email' => 'creator2@example.com',
                'username' => 'creator_two',
                'password' => Hash::make('password'),
                'age' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
