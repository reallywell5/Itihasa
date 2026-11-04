<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\QrScanLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function index()
    {
        $shiftMetrics = $this->getShiftMetrics();
        $activeBroadcasts = Broadcast::active()
            ->forRole('staff')
            ->latest()
            ->get();

        return view('petugas.qrcodes.scan', compact('shiftMetrics', 'activeBroadcasts'));
    }

    /**
     * Endpoint offline cache untuk perangkat scanner (Fitur C Petugas).
     */
    public function offlineCache()
    {
        $staffMuseumId = Auth::user()->museum_id;
        $today = Carbon::today()->toDateString();

        $transactions = Transaction::with(['booking.user', 'booking.museum'])
            ->where('payment_status', 'paid')
            ->whereHas('booking', function ($q) use ($staffMuseumId, $today) {
                if ($staffMuseumId) {
                    $q->where('museum_id', $staffMuseumId);
                }
                $q->whereDate('visit_date', $today);
            })
            ->get()
            ->map(function ($tx) {
                $b = $tx->booking;

                return [
                    'id' => $tx->id,
                    'invoice_code' => $tx->invoice_code,
                    'customer_name' => $b->nama_penanggung_jawab ?? ($b->user->name ?? 'Pengunjung'),
                    'museum_name' => $b->museum->name ?? '-',
                    'visit_date' => $b->visit_date ? Carbon::parse($b->visit_date)->toDateString() : null,
                    'jumlah_anggota' => (int) ($b->jumlah_anggota ?? 1),
                    'is_rombongan' => (bool) ($b->is_rombongan ?? false),
                    'manifest' => is_array($b->manifest) ? $b->manifest : [],
                    'scanned_manifest' => is_array($tx->scanned_manifest) ? $tx->scanned_manifest : [],
                    'scanned_pax' => (int) ($tx->scanned_pax ?? 0),
                    'is_fully_used' => $tx->isFullyUsed(),
                    'used_at' => $tx->used_at?->format('H:i'),
                ];
            });

        return response()->json([
            'museum_id' => $staffMuseumId,
            'date' => $today,
            'total_tickets' => $transactions->count(),
            'tickets' => $transactions,
        ]);
    }

    /**
     * Endpoint sinkronisasi pindaian offline (Fitur C Petugas).
     */
    public function syncOffline(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items) || ! is_array($items)) {
            return response()->json(['synced_count' => 0, 'message' => 'Tidak ada antrean pindaian offline.'], 422);
        }

        $synced = 0;
        $failed = 0;

        foreach ($items as $item) {
            $code = trim($item['invoice_code'] ?? '');
            if (! $code) {
                continue;
            }

            try {
                $tx = Transaction::with('booking')->where('invoice_code', $code)->first();
                if ($tx && $tx->payment_status === 'paid' && ! $tx->isFullyUsed()) {
                    $now = ! empty($item['scanned_at']) ? Carbon::parse($item['scanned_at']) : now();
                    $manifestChecked = is_array($item['manifest_checked'] ?? null) ? $item['manifest_checked'] : [];

                    $totalMembers = (int) ($tx->booking->jumlah_anggota ?? 1);
                    $newPax = ! empty($manifestChecked) ? count($manifestChecked) : $totalMembers;
                    $isFull = $newPax >= $totalMembers;

                    $tx->update([
                        'scanned_by_user_id' => Auth::id(),
                        'scanned_manifest' => ! empty($manifestChecked) ? $manifestChecked : ($tx->booking->manifest ?? []),
                        'scanned_pax' => $newPax,
                        'gate_incident_tag' => $item['gate_incident_tag'] ?? null,
                        'gate_notes' => $item['gate_notes'] ?? null,
                        'used_at' => $isFull ? $now : null,
                    ]);

                    QrScanLog::create([
                        'transaction_id' => $tx->id,
                        'scanned_by' => Auth::id(),
                        'qr_code_input' => $code,
                        'status' => 'success',
                        'message' => 'Validasi Offline Auto-Sync ('.$newPax.'/'.$totalMembers.' orang)',
                        'gate_incident_tag' => $item['gate_incident_tag'] ?? null,
                        'gate_notes' => $item['gate_notes'] ?? null,
                        'scanned_pax' => $newPax,
                        'scanned_at' => $now,
                    ]);

                    $synced++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'synced_count' => $synced,
            'failed_count' => $failed,
            'message' => "{$synced} tiket offline berhasil disinkronisasi ke server.",
        ]);
    }

    /**
     * Validasi QR Code / Invoice di Gate (Mendukung Fitur A, D, E).
     */
    public function validateQr(Request $request)
    {
        $qrInput = trim($request->input('invoice_code') ?? $request->input('qr_code') ?? '');

        if (! $qrInput) {
            return $this->fail('Kode QR / Invoice wajib diisi.');
        }

        return DB::transaction(function () use ($request, $qrInput) {

            $transaction = Transaction::with(['booking.user', 'booking.museum'])
                ->where('invoice_code', $qrInput)
                ->lockForUpdate()
                ->first();

            if (! $transaction) {
                $this->logScan(null, $qrInput, 'failed', 'QR Code tidak valid.');

                return $this->fail('QR Code tidak valid.');
            }

            if ($transaction->payment_status !== 'paid') {
                $this->logScan($transaction->id, $qrInput, 'failed', 'Pembayaran belum selesai.');

                return $this->fail('Pembayaran belum selesai.');
            }

            if ($transaction->isFullyUsed()) {
                $usedTime = $transaction->used_at ? $transaction->used_at->format('d M Y, H:i').' WIB' : 'sebelumnya';
                $this->logScan($transaction->id, $qrInput, 'failed', 'Tiket sudah selesai digunakan seluruhnya.');

                return $this->fail('Tiket sudah digunakan pada '.$usedTime);
            }

            $visitDate = $transaction->booking->visit_date ?? null;

            if ($visitDate) {
                $visitDateString = $visitDate instanceof Carbon
                    ? $visitDate->toDateString()
                    : Carbon::parse($visitDate)->toDateString();

                $today = now()->toDateString();

                if ($visitDateString > $today) {
                    $msg = 'Tiket berlaku untuk tanggal '.
                        Carbon::parse($visitDateString)->translatedFormat('d F Y').
                        ', belum bisa digunakan hari ini.';
                    $this->logScan($transaction->id, $qrInput, 'failed', $msg);

                    return $this->fail($msg);
                }

                if ($visitDateString < $today) {
                    $this->logScan($transaction->id, $qrInput, 'failed', 'Tiket sudah kadaluwarsa.');

                    return $this->fail('Tiket sudah kadaluwarsa.');
                }
            }

            $staffMuseumId = Auth::user()->museum_id;
            if ($staffMuseumId && $transaction->booking?->museum_id !== $staffMuseumId) {
                $museumName = $transaction->booking?->museum?->name ?? 'Museum Lain';
                $msg = "Tiket ini bukan untuk museum ini (Tiket terdaftar untuk: {$museumName}).";
                $this->logScan($transaction->id, $qrInput, 'failed', $msg);

                return $this->fail($msg);
            }

            $museum = $transaction->booking->museum ?? null;

            if ($museum) {
                if ($museum->isClosedOnDate(now())) {
                    $msg = 'Museum tutup (libur) hari ini. Tiket tidak bisa discan.';
                    $this->logScan($transaction->id, $qrInput, 'failed', $msg);

                    return $this->fail($msg);
                }

                if (! $museum->isOpenAt(now())) {
                    $sessions = $museum->sessionsForDate(now());
                    $sessionText = collect($sessions)->map(fn ($s) => "{$s['open']}-{$s['close']}")->implode(', ');

                    $msg = "Sedang di luar jam operasional museum (sesi hari ini: {$sessionText}). Tiket tidak bisa discan sekarang.";
                    $this->logScan($transaction->id, $qrInput, 'failed', $msg);

                    return $this->fail($msg);
                }
            }

            // === FITUR A: MANIFES ROMBONGAN BERTAHAP & FITUR E: GATE NOTES ===
            $booking = $transaction->booking;
            $now = now();
            $gateIncidentTag = $request->input('gate_incident_tag');
            $gateNotes = $request->input('gate_notes');

            $manifestChecked = $request->input('manifest_checked'); // Array of names checked in
            $totalPax = (int) ($booking->jumlah_anggota ?? 1);

            if ($booking->is_rombongan && is_array($manifestChecked) && ! empty($manifestChecked)) {
                // Merge dengan manifest yang sudah pernah masuk
                $existingManifest = is_array($transaction->scanned_manifest) ? $transaction->scanned_manifest : [];
                $updatedManifest = array_values(array_unique(array_merge($existingManifest, $manifestChecked)));
                $scannedPax = count($updatedManifest);
                $isFullyUsed = $scannedPax >= $totalPax;

                $transaction->update([
                    'scanned_by_user_id' => Auth::id(),
                    'scanned_manifest' => $updatedManifest,
                    'scanned_pax' => $scannedPax,
                    'gate_incident_tag' => $gateIncidentTag ?? $transaction->gate_incident_tag,
                    'gate_notes' => $gateNotes ?? $transaction->gate_notes,
                    'used_at' => $isFullyUsed ? $now : null,
                ]);

                $msg = $isFullyUsed
                    ? "Rombongan {$booking->nama_penanggung_jawab} telah masuk lengkap ({$scannedPax}/{$totalPax} orang)."
                    : "Sebagian rombongan ({$scannedPax}/{$totalPax} orang) berhasil masuk. Tiket masih aktif untuk sisa anggota.";

                $this->logScan($transaction->id, $qrInput, 'success', $msg, $gateIncidentTag, $gateNotes, $scannedPax);

                return $this->success($msg)->with('scan_manifest_progress', [
                    'jumlah_anggota' => $totalPax,
                    'scanned_pax' => $scannedPax,
                    'nama_penanggung_jawab' => $booking->nama_penanggung_jawab,
                    'daftar_nama' => $booking->manifest ?? [],
                    'scanned_manifest' => $updatedManifest,
                    'is_fully_used' => $isFullyUsed,
                ]);
            }

            // Normal / Rombongan sekaligus
            $scannedPax = $totalPax;
            $allManifest = is_array($booking->manifest) ? $booking->manifest : [];

            $transaction->update([
                'scanned_by_user_id' => Auth::id(),
                'scanned_manifest' => $allManifest,
                'scanned_pax' => $scannedPax,
                'gate_incident_tag' => $gateIncidentTag,
                'gate_notes' => $gateNotes,
                'used_at' => $now,
            ]);

            $successMsg = 'Tiket valid untuk '.
                ($booking->nama_penanggung_jawab ?? ($booking->user->name ?? '-')).' di '.
                ($booking->museum->name ?? '-');

            if ($booking->is_rombongan) {
                $successMsg .= " (Rombongan lengkap, {$totalPax} orang)";
            }

            $this->logScan($transaction->id, $qrInput, 'success', $successMsg, $gateIncidentTag, $gateNotes, $scannedPax);

            $response = $this->success($successMsg);
            if ($booking->is_rombongan && ! empty($booking->manifest)) {
                $response->with('scan_manifest_progress', [
                    'jumlah_anggota' => $totalPax,
                    'scanned_pax' => $scannedPax,
                    'nama_penanggung_jawab' => $booking->nama_penanggung_jawab,
                    'daftar_nama' => $booking->manifest,
                    'scanned_manifest' => $booking->manifest,
                    'is_fully_used' => true,
                ]);
            }

            return $response;
        });
    }

    /**
     * Hitung metrik shift kerja petugas hari ini (Fitur D Petugas).
     */
    public function getShiftMetrics(): array
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $todayScansCount = QrScanLog::where('scanned_by', $userId)
            ->whereDate('scanned_at', $today)
            ->where('status', 'success')
            ->count();

        $todayPaxCount = Transaction::where('scanned_by_user_id', $userId)
            ->whereDate('updated_at', $today)
            ->sum('scanned_pax');

        $firstScanTime = QrScanLog::where('scanned_by', $userId)
            ->whereDate('scanned_at', $today)
            ->oldest('scanned_at')
            ->value('scanned_at');

        return [
            'today_scans' => $todayScansCount,
            'today_pax' => (int) $todayPaxCount,
            'shift_start' => $firstScanTime ? Carbon::parse($firstScanTime)->format('H:i') : now()->format('H:i'),
            'status' => 'Aktif',
        ];
    }

    private function fail(string $message)
    {
        return back()
            ->with('error', $message)
            ->with('swal_error', $message);
    }

    private function success(string $message)
    {
        return back()
            ->with('success', $message)
            ->with('swal_success', $message);
    }

    private function logScan(?int $transactionId, string $qrInput, string $status, string $message, ?string $tag = null, ?string $notes = null, ?int $pax = null): void
    {
        QrScanLog::create([
            'transaction_id' => $transactionId,
            'scanned_by' => Auth::id(),
            'qr_code_input' => $qrInput,
            'status' => $status,
            'message' => $message,
            'gate_incident_tag' => $tag,
            'gate_notes' => $notes,
            'scanned_pax' => $pax,
            'scanned_at' => now(),
        ]);
    }

    public function riwayat(Request $request)
    {
        $staffMuseumId = Auth::user()->museum_id;

        $query = QrScanLog::with(['transaction.booking.user', 'transaction.booking.museum', 'petugas'])
            ->when($staffMuseumId, function ($q) use ($staffMuseumId) {
                $q->whereHas('transaction.booking', function ($bq) use ($staffMuseumId) {
                    $bq->where('museum_id', $staffMuseumId);
                });
            })
            ->when($request->filled('incident_only'), function ($q) {
                $q->whereNotNull('gate_incident_tag')->orWhereNotNull('gate_notes');
            })
            ->orderByDesc('scanned_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $scans = $query->paginate(20)->withQueryString();

        $baseCountQuery = QrScanLog::when($staffMuseumId, function ($q) use ($staffMuseumId) {
            $q->whereHas('transaction.booking', function ($bq) use ($staffMuseumId) {
                $bq->where('museum_id', $staffMuseumId);
            });
        });

        $totalScan = (clone $baseCountQuery)->count();
        $successScan = (clone $baseCountQuery)->where('status', 'success')->count();
        $failedScan = (clone $baseCountQuery)->where('status', 'failed')->count();
        $todayScan = (clone $baseCountQuery)->whereDate('scanned_at', now()->toDateString())->count();
        $incidentsCount = (clone $baseCountQuery)->whereNotNull('gate_incident_tag')->count();

        return view('petugas.riwayat', compact(
            'scans', 'totalScan', 'successScan', 'failedScan', 'todayScan', 'incidentsCount'
        ));
    }
}
