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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('scanned_by_user_id')->nullable()->after('used_at')->constrained('users')->nullOnDelete();
            $table->json('scanned_manifest')->nullable()->after('scanned_by_user_id');
            $table->unsignedInteger('scanned_pax')->default(0)->after('scanned_manifest');
            $table->string('gate_incident_tag')->nullable()->after('scanned_pax');
            $table->text('gate_notes')->nullable()->after('gate_incident_tag');
        });

        Schema::table('qr_scan_logs', function (Blueprint $table) {
            $table->string('gate_incident_tag')->nullable()->after('message');
            $table->text('gate_notes')->nullable()->after('gate_incident_tag');
            $table->unsignedInteger('scanned_pax')->nullable()->after('gate_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_scan_logs', function (Blueprint $table) {
            $table->dropColumn(['gate_incident_tag', 'gate_notes', 'scanned_pax']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['scanned_by_user_id']);
            $table->dropColumn([
                'scanned_by_user_id',
                'scanned_manifest',
                'scanned_pax',
                'gate_incident_tag',
                'gate_notes',
            ]);
        });
    }
};
