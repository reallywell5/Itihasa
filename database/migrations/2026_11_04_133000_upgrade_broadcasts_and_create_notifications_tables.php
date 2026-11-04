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
        // 1. Tambah kolom category & display_type pada tabel broadcasts jika belum ada
        Schema::table('broadcasts', function (Blueprint $table) {
            if (! Schema::hasColumn('broadcasts', 'category')) {
                $table->string('category')->default('umum')->after('urgency');
            }
            if (! Schema::hasColumn('broadcasts', 'display_type')) {
                $table->string('display_type')->default('bell')->after('target_role'); // bell, banner, popup
            }
        });

        // 2. Tabel riwayat baca broadcast resmi per pengguna
        if (! Schema::hasTable('broadcast_reads')) {
            Schema::create('broadcast_reads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('broadcast_id')->constrained('broadcasts')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('read_at')->useCurrent();
                $table->unique(['broadcast_id', 'user_id']);
            });
        }

        // 3. Tabel notifikasi transaksi & sistem
        if (! Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('category'); // pesanan_baru, pembayaran_sukses, pembayaran_gagal, qr_terbit, validasi_gate, tiket_batal, sistem
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->string('action_url')->nullable();
                $table->string('urgency')->default('info'); // info, success, warning, danger
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_read']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('broadcast_reads');

        Schema::table('broadcasts', function (Blueprint $table) {
            if (Schema::hasColumn('broadcasts', 'display_type')) {
                $table->dropColumn('display_type');
            }
            if (Schema::hasColumn('broadcasts', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};

