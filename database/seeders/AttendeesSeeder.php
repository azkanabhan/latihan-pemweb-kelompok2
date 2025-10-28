<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AttendeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attendees')->insert([
            [
                'username'   => 'Rifki',
                'email'      => 'rifki@example.com',
                'password'   => Hash::make('password123'),
                'age'        => 21,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username'   => 'Latif',
                'email'      => 'latif@example.com',
                'password'   => Hash::make('password123'),
                'age'        => 22,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
