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
        Schema::create('daily_scan_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->foreignId('museum_id')->nullable()->constrained('museums')->nullOnDelete();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('total_scans')->default(0);
            $table->unsignedInteger('successful_scans')->default(0);
            $table->unsignedInteger('failed_scans')->default(0);
            $table->unsignedInteger('total_pax')->default(0);
            $table->unsignedInteger('incidents_count')->default(0);
            $table->string('archive_file')->nullable();
            $table->timestamps();

            $table->unique(['date', 'museum_id', 'scanned_by'], 'daily_scan_unique');
        });

        Schema::table('qr_scan_logs', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('scanned_pax')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_scan_logs', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });

        Schema::dropIfExists('daily_scan_summaries');
    }
};
