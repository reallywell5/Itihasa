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
        Schema::create('museum_special_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('museum_id')->constrained('museums')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_closed')->default(true);
            $table->unsignedInteger('quota_override')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['museum_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('museum_special_dates');
    }
};
