<?php

use App\Models\QrScanLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * 1. Jadwal Pengarsipan Log Scan (> 6 bulan):
 * Mengagregasikan data log lama ke tabel ringkasan harian (daily_scan_summaries)
 * dan mengekspornya ke berkas CSV arsip sebelum dipangkas.
 */
Schedule::command('scan:archive-logs --months=6')
    ->dailyAt('01:00')
    ->name('archive-scan-logs')
    ->withoutOverlapping();

/**
 * 2. Jadwal Auto-Pruning Log Scan:
 * Membuang otomatis log pindaian yang gagal (> 30 hari)
 * dan log yang sudah terarsip (> 6 bulan) agar kapasitas tabel tetap ramping.
 */
Schedule::command('model:prune', ['--model' => [QrScanLog::class]])
    ->dailyAt('02:00')
    ->name('prune-scan-logs')
    ->withoutOverlapping();

/**
 * 3. Jadwal Auto-Nonaktifkan Broadcast Kedaluwarsa:
 * Menonaktifkan otomatis seluruh broadcast resmi yang telah melewati tanggal dan jam kedaluwarsa.
 */
Schedule::command('broadcast:deactivate-expired')
    ->everyFiveMinutes()
    ->name('deactivate-expired-broadcasts')
    ->withoutOverlapping();
