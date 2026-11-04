<?php

namespace Tests\Feature;

use App\Models\DailyScanSummary;
use App\Models\QrScanLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrScanOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_prunable_identifies_failed_logs_older_than_30_days(): void
    {
        // 1. Failed log > 30 days -> harus diprune
        $oldFailed = QrScanLog::create([
            'qr_code_input' => 'TEST-OLD-FAIL',
            'status' => 'failed',
            'message' => 'Kode salah',
            'scanned_at' => now()->subDays(35),
        ]);
        $oldFailed->created_at = now()->subDays(35);
        $oldFailed->save();

        // 2. Recent failed log (10 days) -> TIDAK boleh diprune
        $recentFailed = QrScanLog::create([
            'qr_code_input' => 'TEST-RECENT-FAIL',
            'status' => 'failed',
            'message' => 'Kode salah',
            'scanned_at' => now()->subDays(10),
        ]);
        $recentFailed->created_at = now()->subDays(10);
        $recentFailed->save();

        // 3. Success log (40 days, unarchived) -> TIDAK boleh diprune
        $successLog = QrScanLog::create([
            'qr_code_input' => 'TEST-SUCCESS',
            'status' => 'success',
            'message' => 'Tiket valid',
            'scanned_at' => now()->subDays(40),
        ]);
        $successLog->created_at = now()->subDays(40);
        $successLog->save();

        $prunableIds = (new QrScanLog)->prunable()->pluck('id')->all();

        $this->assertContains($oldFailed->id, $prunableIds);
        $this->assertNotContains($recentFailed->id, $prunableIds);
        $this->assertNotContains($successLog->id, $prunableIds);
    }

    public function test_archive_command_creates_daily_summary_and_csv(): void
    {
        Storage::fake('local');

        $oldSuccess = QrScanLog::create([
            'qr_code_input' => 'INV-HISTORIS-001',
            'status' => 'success',
            'message' => 'Tiket valid',
            'scanned_pax' => 2,
            'is_archived' => false,
            'scanned_at' => now()->subMonths(7),
        ]);
        $oldSuccess->created_at = now()->subMonths(7);
        $oldSuccess->save();

        $this->artisan('scan:archive-logs', ['--months' => 6])
            ->assertSuccessful();

        // Harus tercatat di DailyScanSummary
        $this->assertDatabaseHas('daily_scan_summaries', [
            'total_scans' => 1,
            'successful_scans' => 1,
            'total_pax' => 2,
        ]);

        // Status log berubah menjadi is_archived = true
        $oldSuccess->refresh();
        $this->assertTrue((bool) $oldSuccess->is_archived);

        // Berkas CSV arsip harus dibuat
        $yearMonth = now()->subMonths(7)->format('Y-m');
        Storage::disk('local')->assertExists("archives/scans/archive_scans_{$yearMonth}.csv");
    }
}
