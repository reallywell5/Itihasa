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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('museum_id')->nullable()->constrained('museums')->cascadeOnDelete();
            $table->string('code')->index();
            $table->string('title');
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->unsignedInteger('discount_value');
            $table->unsignedInteger('min_total_price')->default(0);
            $table->unsignedInteger('max_discount')->nullable();
            $table->unsignedInteger('quota')->default(100);
            $table->unsignedInteger('used_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('promo_id')->nullable()->after('museum_id')->constrained('promos')->nullOnDelete();
            $table->string('promo_code')->nullable()->after('promo_id');
            $table->unsignedInteger('discount_amount')->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn(['promo_id', 'promo_code', 'discount_amount']);
        });

        Schema::dropIfExists('promos');
    }
};
