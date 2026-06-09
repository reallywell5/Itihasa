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
        Schema::table('museums', function (Blueprint $table) {
            // Hanya buat kolom jika belum ada di database
            if (!Schema::hasColumn('museums', 'opening_time')) {
                $table->time('opening_time')->nullable()->after('description');
            }
            if (!Schema::hasColumn('museums', 'closing_time')) {
                $table->time('closing_time')->nullable()->after('opening_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('museums', function (Blueprint $table) {
            if (Schema::hasColumn('museums', 'opening_time')) {
                $table->dropColumn('opening_time');
            }
            if (Schema::hasColumn('museums', 'closing_time')) {
                $table->dropColumn('closing_time');
            }
        });
    }
};
