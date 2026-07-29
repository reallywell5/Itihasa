<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $transaction = Transaction::with([
            'booking.user',
            'booking.museum'
        ])->where('invoice_code', trim($request->qr_code))->first();

        if (!$transaction) {
            return back()->with('error', 'QR Code tidak valid.');
        }

        if ($transaction->payment_status !== 'paid') {
            return back()->with('error', 'Pembayaran belum selesai.');
        }

        if ($transaction->used_at) {
            return back()->with('error', 'Ticket sudah digunakan.');
        }

        $visitDate = $transaction->booking->visit_date ?? null;

        if ($visitDate) {
            $visitDateString = $visitDate instanceof Carbon
                ? $visitDate->toDateString()
                : Carbon::parse($visitDate)->toDateString();

            $today = now()->toDateString();

            // Tiket dipakai LEBIH AWAL dari tanggal kunjungan
            if ($visitDateString > $today) {
                return back()->with(
                    'error',
                    'Tiket ini berlaku untuk tanggal ' .
                    Carbon::parse($visitDateString)->translatedFormat('d F Y') .
                    ', belum bisa digunakan hari ini.'
                );
            }

            // Tiket dipakai SETELAH tanggal kunjungan lewat
            if ($visitDateString < $today) {
                return back()->with('error', 'Tiket sudah kadaluwarsa.');
            }
        }

        // Cek jam operasional museum (hanya berlaku kalau hari ini = visit_date)
        $museum = $transaction->booking->museum ?? null;

        if ($museum && $museum->opening_time && $museum->closing_time) {
            $now = now();

            // Carbon::parse pada string jam saja (mis. "08:00:00") otomatis
            // memakai tanggal hari ini, jadi aman langsung dibandingkan dengan now()
            $openingTime = Carbon::parse($museum->opening_time);
            $closingTime = Carbon::parse($museum->closing_time);

            if ($now->lt($openingTime) || $now->gt($closingTime)) {
                return back()->with(
                    'error',
                    'Museum hanya buka pukul ' .
                    $openingTime->format('H:i') .
                    ' - ' .
                    $closingTime->format('H:i') .
                    '. Tiket tidak bisa discan di luar jam operasional.'
                );
            }
        }

        $transaction->update([
            'used_at' => now()
        ]);

        return back()->with(
            'success',
            'Tiket valid untuk ' .
            ($transaction->booking->user->name ?? '-') .
            ' di ' .
            ($transaction->booking->museum->name ?? '-')
        );
    }

    public function riwayat()
    {
        // Hanya tiket yang sudah discan, diurutkan dari yang paling baru discan
        $scans = Transaction::with([
            'booking.user',
            'booking.museum'
        ])
            ->whereNotNull('used_at')
            ->orderByDesc('used_at')
            ->get();

        $totalScan = $scans->count();
        $todayScan = $scans->filter(
            fn ($t) => $t->used_at->isToday()
        )->count();

        return view('petugas.riwayat', compact(
            'scans',
            'totalScan',
            'todayScan'
        ));
    }
}
