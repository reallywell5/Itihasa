<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\QrScanLog;
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
        $request->validate([
            'qr_code' => 'required'
        ]);

        $qrInput = trim($request->qr_code);

        return DB::transaction(function () use ($qrInput) {

            // lockForUpdate() -> mencegah 2 request scan bersamaan lolos validasi bersamaan
            $transaction = Transaction::with(['booking.user', 'booking.museum'])
                ->where('invoice_code', $qrInput)
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                $this->logScan(null, $qrInput, 'failed', 'QR Code tidak valid.');
                return $this->fail('QR Code tidak valid.');
            }

            if ($transaction->payment_status !== 'paid') {
                $this->logScan($transaction->id, $qrInput, 'failed', 'Pembayaran belum selesai.');
                return $this->fail('Pembayaran belum selesai.');
            }

            if ($transaction->used_at) {
                $this->logScan($transaction->id, $qrInput, 'failed', 'Tiket sudah digunakan.');
                return $this->fail('Tiket sudah digunakan.');
            }

            $visitDate = $transaction->booking->visit_date ?? null;

            if ($visitDate) {
                $visitDateString = $visitDate instanceof Carbon
                    ? $visitDate->toDateString()
                    : Carbon::parse($visitDate)->toDateString();

                $today = now()->toDateString();

                if ($visitDateString > $today) {
                    $msg = 'Tiket berlaku untuk tanggal ' .
                        Carbon::parse($visitDateString)->translatedFormat('d F Y') .
                        ', belum bisa digunakan hari ini.';
                    $this->logScan($transaction->id, $qrInput, 'failed', $msg);
                    return $this->fail($msg);
                }

                if ($visitDateString < $today) {
                    $this->logScan($transaction->id, $qrInput, 'failed', 'Tiket sudah kadaluwarsa.');
                    return $this->fail('Tiket sudah kadaluwarsa.');
                }
            }

            $museum = $transaction->booking->museum ?? null;

            if ($museum && $museum->opening_time && $museum->closing_time) {
                $now = now();
                $openingTime = Carbon::parse($museum->opening_time);
                $closingTime = Carbon::parse($museum->closing_time);

                if ($now->lt($openingTime) || $now->gt($closingTime)) {
                    $msg = 'Museum hanya buka pukul ' .
                        $openingTime->format('H:i') . ' - ' . $closingTime->format('H:i') .
                        '. Tiket tidak bisa discan di luar jam operasional.';
                    $this->logScan($transaction->id, $qrInput, 'failed', $msg);
                    return $this->fail($msg);
                }
            }

            // Semua validasi lolos -> tandai sebagai used
            $transaction->update(['used_at' => now()]);

            $successMsg = 'Tiket valid untuk ' .
                ($transaction->booking->user->name ?? '-') . ' di ' .
                ($transaction->booking->museum->name ?? '-');

            $this->logScan($transaction->id, $qrInput, 'success', $successMsg);

            return $this->success($successMsg);
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
            'scanned_by'     => auth()->id(),
            'qr_code_input'  => $qrInput,
            'status'         => $status,
            'message'        => $message,
            'scanned_at'     => now(),
        ]);
    }

    public function riwayat(Request $request)
    {
        $query = QrScanLog::with(['transaction.booking.user', 'transaction.booking.museum', 'petugas'])
            ->orderByDesc('scanned_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $scans = $query->paginate(20)->withQueryString();

        $totalScan   = QrScanLog::count();
        $successScan = QrScanLog::where('status', 'success')->count();
        $failedScan  = QrScanLog::where('status', 'failed')->count();
        $todayScan   = QrScanLog::whereDate('scanned_at', now()->toDateString())->count();

        return view('petugas.riwayat', compact(
            'scans', 'totalScan', 'successScan', 'failedScan', 'todayScan'
        ));
    }
}
