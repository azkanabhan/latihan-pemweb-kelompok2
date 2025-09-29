<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'name'        => 'Tech Conference 2025',
                'description' => 'Konferensi teknologi tahunan dengan pembicara internasional.',
                'location'    => 'Jakarta Convention Center',
                'date'        => now()->addDays(30),
                'capacity'    => 500,
                'img_thumbnail' => 'tech_conf.jpg',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Music Festival',
                'description' => 'Festival musik dengan berbagai genre dan artis populer.',
                'location'    => 'Stadion Gelora Bung Karno',
                'date'        => now()->addDays(60),
                'capacity'    => 2000,
                'img_thumbnail' => 'music_fest.jpg',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
