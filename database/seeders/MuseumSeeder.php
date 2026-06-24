<?php

namespace Database\Seeders;

use App\Models\Museum;
use Illuminate\Database\Seeder;

class MuseumSeeder extends Seeder
{
    public function run()
    {
        $museums = [
            [
                'name' => 'National Museum',
                'address' => 'Janpath, New Delhi, India',
                'description' => 'The National Museum in New Delhi is one of the largest museums in India. It holds a vast collection of artifacts from different periods of Indian history.',
                'opening_time' => '10:00:00',
                'closing_time' => '18:00:00',
            ],
            [
                'name' => 'Chhatrapati Shivaji Maharaj Vastu Sangrahalaya',
                'address' => '159-161, Mahatma Gandhi Road, Mumbai, India',
                'description' => 'Formerly known as the Prince of Wales Museum, this is one of the premier art and history museums in India.',
                'opening_time' => '10:15:00',
                'closing_time' => '18:00:00',
            ],
            [
                'name' => 'Indian Museum',
                'address' => '27, Jawaharlal Nehru Road, Kolkata, India',
                'description' => 'The Indian Museum in Kolkata is the oldest and largest museum in India. It has a rich collection of natural history specimens and artifacts.',
                'opening_time' => '10:00:00',
                'closing_time' => '17:00:00',
            ],
            [
                'name' => 'Salar Jung Museum',
                'address' => 'Salar Jung Road, Hyderabad, India',
                'description' => 'The Salar Jung Museum is one of the three National Museums of India. It has a large collection of art, artifacts, and antiques.',
                'opening_time' => '10:00:00',
                'closing_time' => '17:00:00',
            ],
            [
                'name' => 'Government Museum',
                'address' => 'Egmore, Chennai, India',
                'description' => 'The Government Museum in Chennai is one of the oldest museums in India. It has a significant collection of bronze sculptures and artifacts.',
                'opening_time' => '09:30:00',
                'closing_time' => '17:00:00',
            ],
            [
                'name' => 'Albert Hall Museum',
                'address' => 'Rambagh, Jaipur, India',
                'description' => 'The Albert Hall Museum in Jaipur is the oldest museum in Rajasthan. It features a rich collection of artifacts, paintings, and sculptures.',
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
            ],
        ];

        foreach ($museums as $museum) {
            if (!Museum::where('name', $museum['name'])->exists()) {
                Museum::create($museum);
                $this->command->info("✅ Created museum: {$museum['name']}");
            } else {
                $this->command->info("⏭️ Museum already exists: {$museum['name']}");
            }
        }

        $this->command->info('✅ Museums seeding completed!');
    }
}