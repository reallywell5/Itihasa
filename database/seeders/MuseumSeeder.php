<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Museum;

class MuseumSeeder extends Seeder
{
    public function run(): void
    {
        Museum::insert([
            [
                'name' => 'Museum Barli',
                'address' => 'Bandung',
                'description' => 'Museum seni karya Barli.',
                'image' => 'museums/barli.jpg',
                'opening_time' => '08:00:00',
                'closing_time' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Museum Geologi',
                'address' => 'Bandung',
                'description' => 'Museum geologi terbesar di Indonesia.',
                'image' => 'museums/geologi.jpg',
                'opening_time' => '08:00:00',
                'closing_time' => '16:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Museum Konferensi Asia Afrika',
                'address' => 'Bandung',
                'description' => 'Museum sejarah Konferensi Asia Afrika.',
                'image' => 'museums/kaa.jpg',
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
