<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('transaction_detail_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('qr_code');

            $table->boolean('scan_status')
                ->default(false);

            $table->dateTime('scanned_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
