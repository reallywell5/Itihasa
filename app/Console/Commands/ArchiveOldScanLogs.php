<?php

namespace App\Console\Commands;

use App\Models\DailyScanSummary;
use App\Models\QrScanLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArchiveOldScanLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:archive-logs
                            {--months=6 : Batas usia log dalam bulan yang akan diarsipkan}
                            {--delete-immediately : Langsung hapus log detail dari tabel setelah sukses diarsipkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Arsipkan log pemindaian QR tiket lama (> 6 bulan) ke ringkasan harian dan berkas CSV arsip.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $months = max(1, (int) $this->option('months'));
        $cutoffDate = now()->subMonths($months)->endOfDay();

        $this->info("Memulai proses pengarsipan log scan lebih lama dari {$months} bulan (sebelum {$cutoffDate->toDateTimeString()})...");

        // Ambil data log yang belum pernah diarsipkan
        $unarchivedLogsQuery = QrScanLog::with(['transaction.booking', 'petugas'])
            ->where('created_at', '<=', $cutoffDate)
            ->where('is_archived', false)
            ->orderBy('id');

        $totalToArchive = $unarchivedLogsQuery->count();

        if ($totalToArchive === 0) {
            $this->info('Tidak ada log scan lawas yang perlu diarsipkan saat ini.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$totalToArchive} baris log yang akan diarsipkan.");

        $processedCount = 0;
        $exportDirectory = 'archives/scans';
        if (! Storage::disk('local')->exists($exportDirectory)) {
            Storage::disk('local')->makeDirectory($exportDirectory);
        }

        // Proses per chunk 500 data agar hemat memori RAM
        $unarchivedLogsQuery->chunk(500, function ($logs) use (&$processedCount, $exportDirectory) {
            // Kelompokkan log berdasarkan bulan untuk file CSV dan ringkasan harian
            $groupedByMonth = $logs->groupBy(function ($log) {
                return Carbon::parse($log->scanned_at ?? $log->created_at)->format('Y-m');
            });

            foreach ($groupedByMonth as $yearMonth => $monthLogs) {
                $csvFilename = "{$exportDirectory}/archive_scans_{$yearMonth}.csv";
                $csvExists = Storage::disk('local')->exists($csvFilename);

                $csvHandle = fopen('php://temp', 'r+');

                // Jika berkas baru, tulis header CSV
                if (! $csvExists) {
                    fputcsv($csvHandle, [
                        'ID',
                        'Transaction ID',
                        'Scanned By User ID',
                        'Petugas Name',
                        'Museum ID',
                        'QR Code / Invoice',
                        'Status',
                        'Message',
                        'Gate Tag',
                        'Gate Notes',
                        'Pax',
                        'Scanned At',
                        'Created At',
                    ]);
                }

                // Kelompokkan untuk ringkasan harian: tanggal + museum + petugas
                $dailyGroups = [];

                foreach ($monthLogs as $log) {
                    $dateStr = Carbon::parse($log->scanned_at ?? $log->created_at)->toDateString();
                    $museumId = $log->transaction?->booking?->museum_id ?? $log->petugas?->museum_id;
                    $petugasId = $log->scanned_by;
                    $key = "{$dateStr}_{$museumId}_{$petugasId}";

                    if (! isset($dailyGroups[$key])) {
                        $dailyGroups[$key] = [
                            'date' => $dateStr,
                            'museum_id' => $museumId,
                            'scanned_by' => $petugasId,
                            'total_scans' => 0,
                            'successful_scans' => 0,
                            'failed_scans' => 0,
                            'total_pax' => 0,
                            'incidents_count' => 0,
                            'archive_file' => basename($csvFilename),
                        ];
                    }

                    $dailyGroups[$key]['total_scans']++;
                    if ($log->status === 'success') {
                        $dailyGroups[$key]['successful_scans']++;
                        $dailyGroups[$key]['total_pax'] += (int) ($log->scanned_pax ?? 1);
                    } else {
                        $dailyGroups[$key]['failed_scans']++;
                    }

                    if (! empty($log->gate_incident_tag)) {
                        $dailyGroups[$key]['incidents_count']++;
                    }

                    // Tulis baris CSV detail
                    fputcsv($csvHandle, [
                        $log->id,
                        $log->transaction_id ?? '',
                        $log->scanned_by ?? '',
                        $log->petugas?->name ?? 'System',
                        $museumId ?? '',
                        $log->qr_code_input,
                        $log->status,
                        $log->message,
                        $log->gate_incident_tag ?? '',
                        $log->gate_notes ?? '',
                        $log->scanned_pax ?? 1,
                        $log->scanned_at ? $log->scanned_at->toDateTimeString() : '',
                        $log->created_at ? $log->created_at->toDateTimeString() : '',
                    ]);
                }

                rewind($csvHandle);
                $csvData = stream_get_contents($csvHandle);
                fclose($csvHandle);

                // Simpan atau tambahkan ke CSV
                if ($csvExists) {
                    $existing = Storage::disk('local')->get($csvFilename);
                    Storage::disk('local')->put($csvFilename, $existing.$csvData);
                } else {
                    Storage::disk('local')->put($csvFilename, $csvData);
                }

                // Update atau buat entri DailyScanSummary
                foreach ($dailyGroups as $group) {
                    $summary = DailyScanSummary::firstOrNew([
                        'date' => $group['date'],
                        'museum_id' => $group['museum_id'],
                        'scanned_by' => $group['scanned_by'],
                    ]);

                    $summary->total_scans += $group['total_scans'];
                    $summary->successful_scans += $group['successful_scans'];
                    $summary->failed_scans += $group['failed_scans'];
                    $summary->total_pax += $group['total_pax'];
                    $summary->incidents_count += $group['incidents_count'];
                    $summary->archive_file = $group['archive_file'];
                    $summary->save();
                }

                // Tandai log sebagai sudah diarsipkan
                $logIds = $monthLogs->pluck('id')->all();
                QrScanLog::whereIn('id', $logIds)->update(['is_archived' => true]);
                $processedCount += count($logIds);
            }
        });

        $this->info("Berhasil mengarsipkan {$processedCount} baris log ke ringkasan harian dan berkas CSV arsip ({$exportDirectory}/).");

        if ($this->option('delete-immediately')) {
            $deleted = QrScanLog::where('is_archived', true)
                ->where('created_at', '<=', $cutoffDate)
                ->delete();
            $this->info("{$deleted} log yang telah diarsipkan berhasil dihapus dari tabel utama.");
        }

        return self::SUCCESS;
    }
}
