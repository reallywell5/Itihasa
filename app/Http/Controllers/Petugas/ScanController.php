<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\QrScanLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function index()
    {
        return view('petugas.qrcodes.scan');
    }

    public function validateQr(Request $request)
    {
        $qrInput = trim($request->input('invoice_code') ?? $request->input('qr_code') ?? '');

        if (! $qrInput) {
            return $this->fail('Kode QR / Invoice wajib diisi.');
        }

        return DB::transaction(function () use ($qrInput) {

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

            if ($transaction->used_at) {
                $this->logScan($transaction->id, $qrInput, 'failed', 'Tiket sudah digunakan.');

                return $this->fail('Tiket sudah digunakan pada '.$transaction->used_at->format('d M Y, H:i').' WIB.');
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

            $staffMuseumId = auth()->user()->museum_id;
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

            // Semua validasi lolos -> tandai sebagai used
            $now = now();
            $transaction->update(['used_at' => $now]);

            QrCode::where('transaction_id', $transaction->id)->update([
                'scan_status' => 'used',
                'scanned_at' => $now,
            ]);

            $booking = $transaction->booking;

            $successMsg = 'Tiket valid untuk '.
                ($booking->user->name ?? '-').' di '.
                ($booking->museum->name ?? '-');

            if ($booking->is_rombongan) {
                $successMsg .= ' (Rombongan, '.$booking->jumlah_anggota.' orang)';
            }

            $this->logScan($transaction->id, $qrInput, 'success', $successMsg);

            // Kirim data manifes ke view kalau ini booking rombongan,
            // supaya petugas bisa langsung cek daftar nama rombongan
            $response = $this->success($successMsg);
            if ($booking->is_rombongan && ! empty($booking->manifest)) {
                $response->with('scan_manifest', [
                    'jumlah_anggota' => $booking->jumlah_anggota,
                    'nama_penanggung_jawab' => $booking->nama_penanggung_jawab,
                    'daftar_nama' => $booking->manifest,
                ]);
            }

            return $response;
        });
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

    private function logScan(?int $transactionId, string $qrInput, string $status, string $message): void
    {
        QrScanLog::create([
            'transaction_id' => $transactionId,
            'scanned_by' => auth()->id(),
            'qr_code_input' => $qrInput,
            'status' => $status,
            'message' => $message,
            'scanned_at' => now(),
        ]);
    }

    public function riwayat(Request $request)
    {
        $staffMuseumId = auth()->user()->museum_id;

        $query = QrScanLog::with(['transaction.booking.user', 'transaction.booking.museum', 'petugas'])
            ->when($staffMuseumId, function ($q) use ($staffMuseumId) {
                $q->whereHas('transaction.booking', function ($bq) use ($staffMuseumId) {
                    $bq->where('museum_id', $staffMuseumId);
                });
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

        return view('petugas.riwayat', compact(
            'scans', 'totalScan', 'successScan', 'failedScan', 'todayScan'
        ));
    }
}
