<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::insert([

            // Museum Barli
            [
                'museum_id' => 1,
                'ticket_name' => 'Dewasa',
                'price' => 20000,
                'slot' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'museum_id' => 1,
                'ticket_name' => 'Anak-anak',
                'price' => 10000,
                'slot' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Museum Geologi
            [
                'museum_id' => 2,
                'ticket_name' => 'Dewasa',
                'price' => 25000,
                'slot' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'museum_id' => 2,
                'ticket_name' => 'Pelajar',
                'price' => 15000,
                'slot' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Museum KAA
            [
                'museum_id' => 3,
                'ticket_name' => 'Umum',
                'price' => 15000,
                'slot' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
