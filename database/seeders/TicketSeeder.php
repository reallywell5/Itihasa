<?php

namespace Database\Seeders;

use App\Models\Museum;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua museum
        $museums = Museum::all();

        if ($museums->isEmpty()) {
            $this->command->error('❌ No museums found. Please run MuseumSeeder first.');
            return;
        }

        $ticketTypes = [
            ['ticket_name' => 'Adult', 'price' => 25.00, 'slot' => 100],
            ['ticket_name' => 'Student', 'price' => 15.00, 'slot' => 80],
            ['ticket_name' => 'Child', 'price' => 10.00, 'slot' => 60],
            ['ticket_name' => 'Senior', 'price' => 12.00, 'slot' => 50],
        ];

        foreach ($museums as $museum) {
            foreach ($ticketTypes as $ticket) {
                // Cek apakah tiket sudah ada untuk museum ini
                $exists = Ticket::where('museum_id', $museum->id)
                    ->where('ticket_name', $ticket['ticket_name'])
                    ->exists();

                if (!$exists) {
                    Ticket::create([
                        'museum_id' => $museum->id,
                        'ticket_name' => $ticket['ticket_name'],
                        'price' => $ticket['price'],
                        'slot' => $ticket['slot'],
                    ]);
                    $this->command->info("✅ Created ticket: {$ticket['ticket_name']} for {$museum->name}");
                } else {
                    $this->command->info("⏭️ Ticket already exists: {$ticket['ticket_name']} for {$museum->name}");
                }
            }
        }

        $this->command->info('✅ Tickets seeding completed!');
    }
}