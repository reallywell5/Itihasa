<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class QRCodeController extends Controller
{
    /**
     * Menampilkan daftar semua tiket beserta status QR-nya.
     * Sumber data langsung dari transactions, bukan tabel qr_codes lagi.
     */
    public function index()
    {
        $transactions = Transaction::with([
            'booking.user',
            'booking.museum',
        ])->latest()->get();

        $totalQr  = $transactions->count();
        $usedQr   = $transactions->whereNotNull('used_at')->count();
        $activeQr = $transactions->where('payment_status', 'paid')
            ->whereNull('used_at')
            ->count();

        return view('petugas.qrcodes.index', compact(
            'transactions',
            'totalQr',
            'usedQr',
            'activeQr'
        ));
    }

    /**
     * Menampilkan QR code satu tiket dalam ukuran besar
     * (misal untuk dicetak / ditunjukkan penuh layar oleh petugas).
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['booking.user', 'booking.museum']);

        return view('petugas.qrcodes.show', compact('transaction'));
    }
}
