<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::latest()->get();

        return view('admin.qrcodes.index', compact('qrCodes'));
    }

    public function create()
    {
        $transactionDetails = TransactionDetail::all();

        return view('admin.qrcodes.create', compact('transactionDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_detail_id' => 'required',
            'qr_code' => 'required|string|unique:qr_codes,qr_code',
            'scan_status' => 'required|string',
        ]);

        QrCode::create($request->only([
            'transaction_detail_id',
            'qr_code',
            'scan_status',
        ]));

        // Disesuaikan dengan rute admin jika Anda menggunakan prefix admin di route
        return redirect()->route('admin.qrcodes.index')
            ->with('success', 'QR Code berhasil dibuat.');
    }

    // Mengubah $qrcode menjadi $qrCode agar Route Model Binding bekerja dengan tepat
    public function show(QrCode $qrCode)
    {
        return view('admin.qrcodes.show', compact('qrCode'));
    }

    public function scan()
    {
        // Diarahkan ke folder admin juga agar konsisten
        return view('admin.qrcodes.scan');
    }

    public function validateQr(Request $request)
    {
        $qr = QrCode::where('qr_code', $request->qr_code)->first();

        if (!$qr) {
            return back()->with('error', 'QR Code tidak ditemukan.');
        }

        if ($qr->scan_status === 'used') {
            return back()->with('error', 'QR Code sudah digunakan.');
        }

        $qr->update([
            'scan_status' => 'used',
            'scanned_at' => now(),
        ]);

        return back()->with('success', 'QR Code valid.');
    }
}
