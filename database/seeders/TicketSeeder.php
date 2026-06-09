<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create([
            'museum_id' => 1,
            'ticket_name' => 'Regular Ticket',
            'price' => 20000,
            'slot' => 100
        ]);

        Ticket::create([
            'museum_id' => 2,
            'ticket_name' => 'VIP Ticket',
            'price' => 50000,
            'slot' => 50
        ]);
    }
}
