<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_scan_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->onDelete('cascade');

            $table->foreignId('scanned_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->string('qr_code_input'); // kode yang diinput/discan (buat jaga2 kalau transaksi tidak ketemu)
            $table->enum('status', ['success', 'failed']);
            $table->string('message');
            $table->timestamp('scanned_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_scan_logs');
    }
};
