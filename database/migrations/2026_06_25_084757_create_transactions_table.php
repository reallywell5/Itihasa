<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            $table->string('invoice_code')->unique();
            $table->string('payment_method');

            $table->integer('subtotal');
            $table->integer('total_amount');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
            ])->default('pending');

            // Batas waktu pembayaran
            $table->timestamp('expired_at')->nullable();

            // Waktu tiket digunakan
            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('transactions');

        Schema::enableForeignKeyConstraints();
    }
};
