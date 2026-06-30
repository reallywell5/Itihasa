<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
        ])->where('invoice_code', $request->qr_code)->first();

        if (!$transaction) {
            return back()->with('error', 'QR Code tidak valid.');
        }

        if ($transaction->used_at) {
            return back()->with('error', 'Ticket sudah digunakan.');
        }

        if ($transaction->payment_status !== 'paid') {
            return back()->with('error', 'Pembayaran belum selesai.');
        }

        $transaction->update([
            'used_at' => now()
        ]);

        return back()->with(
            'success',
            'Tiket valid untuk ' .
            $transaction->user->name .
            ' di ' .
            $transaction->museum->name
        );
    }

    public function riwayat()
    {
        $transactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])->latest()->get();

        $totalScan = $transactions->count();
        $validScan = $transactions->whereNotNull('used_at')->count();
        $usedScan = $transactions->whereNotNull('used_at')->count();

        return view('petugas.riwayat', compact(
            'transactions',
            'totalScan',
            'validScan',
            'usedScan'
        ));
    }
}
