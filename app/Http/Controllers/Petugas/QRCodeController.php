<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\Transaction;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::with([
        'transaction.booking.user',
        'transaction.booking.museum'
    ])->latest()->get();

        return view('petugas.qrcodes.index', compact('qrCodes'));
    }

    public function create()
    {
        $transactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])
        ->where('payment_status', 'paid')
        ->latest()
        ->get();

        return view('petugas.qrcodes.create', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id'
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        // Cek apakah QR sudah pernah dibuat
        $existingQr = QrCode::where('transaction_id', $transaction->id)->first();

        if ($existingQr) {
            return back()->with('error', 'QR Code untuk transaksi ini sudah ada.');
        }

        QrCode::create([
            'transaction_id' => $transaction->id,
            'qr_code' => $transaction->invoice_code,
            'scan_status' => 'pending',
        ]);

        return redirect()
            ->route('petugas.qrcodes.index')
            ->with('success', 'QR Code berhasil dibuat.');
    }

    public function show(QrCode $qrCode)
    {
        $qrCode->load([
            'transaction.booking.user',
            'transaction.booking.museum'
        ]);

        return view('petugas.qrcodes.show', compact('qrCode'));
    }

    public function scan(Request $request)
    {
        $qr = null;

        if ($request->invoice_code) {
            $qr = QrCode::with([
                'transaction.booking.user',
                'transaction.booking.museum'
            ])
            ->where('qr_code', trim($request->invoice_code))
            ->first();
        }

        return view('petugas.validasi', compact('qr'));
    }

    public function validateQr(Request $request)
    {
        $request->validate([
            'invoice_code' => 'required'
        ]);

        $invoiceCode = trim($request->invoice_code);

        $qr = QrCode::with([
            'transaction.booking.user',
            'transaction.booking.museum'
        ])
        ->where('qr_code', trim($request->invoice_code))
        ->first();

        if (!$qr) {
            return back()->with('error', 'QR Code tidak ditemukan.');
        }

        if ($qr->scan_status === 'used') {
            return back()->with('error', 'QR Code sudah digunakan.');
        }

        // update status
        $qr->update([
            'scan_status' => 'used',
            'scanned_at' => now(),
        ]);

        return redirect()->route('ticket.thankyou', $qr->id);

        // update transaksi juga
        if ($qr->transaction) {
            $qr->transaction->update([
                'used_at' => now()
            ]);
        }

        return redirect()
            ->route('petugas.scan', [
                'invoice_code' => $qr->qr_code
            ])
            ->with('success', 'Tiket berhasil digunakan.');
    }

    public function riwayat()
    {
        $qrCodes = QrCode::with([
            'transaction.booking.user',
            'transaction.booking.museum'
        ])
        ->latest()
        ->get();

        return view('petugas.riwayat', compact('qrCodes'));
    }

    public function thankYou(QrCode $qrCode)
    {
        $qrCode->load([
            'transaction.booking.user',
            'transaction.booking.museum'
        ]);

        return view('user.thankyou', compact('qrCode'));
    }
}
