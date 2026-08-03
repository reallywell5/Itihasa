<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('nama_penanggung_jawab')->after('user_id');
            $table->unsignedInteger('jumlah_anggota')->after('nama_penanggung_jawab');
            $table->string('kota_asal')->after('jumlah_anggota');
            $table->string('no_hp')->after('kota_asal');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['nama_penanggung_jawab', 'jumlah_anggota', 'kota_asal', 'no_hp']);
        });
    }
};
