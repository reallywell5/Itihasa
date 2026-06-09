<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Museum;

class MuseumSeeder extends Seeder
{
    public function run(): void
    {
        Museum::create([
            'name' => 'Museum Geologi',
            'address' => 'Bandung',
            'description' => 'Museum batuan dan fosil',
            'opening_hours' => '08:00:00'
        ]);

        Museum::create([
            'name' => 'Museum Asia Afrika',
            'address' => 'Bandung',
            'description' => 'Museum sejarah konferensi',
            'opening_hours' => '09:00:00'
        ]);
    }
}
