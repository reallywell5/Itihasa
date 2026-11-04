<?php

namespace App\Console\Commands;

use App\Models\Broadcast;
use Illuminate\Console\Command;

class DeactivateExpiredBroadcasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menonaktifkan otomatis broadcast resmi yang telah melewati tanggal dan jam kedaluwarsa';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Broadcast::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);

        $this->info("Berhasil menonaktifkan {$count} broadcast yang telah kedaluwarsa.");

        return Command::SUCCESS;
    }
}

