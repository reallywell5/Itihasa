<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            $table->string('invoice_code')->unique();
            $table->string('payment_method');

            $table->integer('subtotal');
            $table->integer('service_fee')->default(2000);
            $table->integer('total_amount');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed'
            ])->default('pending');

            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });

        // Enable lagi
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('transactions');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
