<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creator One',
                'username' => 'creator1',
                'email' => 'creator1@example.com',
                'password' => Hash::make('password'),
                'role' => 'creator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creator Two',
                'username' => 'creator2',
                'email' => 'creator2@example.com',
                'password' => Hash::make('password'),
                'role' => 'creator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


