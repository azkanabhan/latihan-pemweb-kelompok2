<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendeesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendees')->insert([
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password123'),
                'age' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password123'),
                'age' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
