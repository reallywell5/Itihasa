<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentWebController extends Controller
{
    public function index()
    {
        // 1. Otomatis update status PAYMENT menjadi failed jika TRANSACTION-nya sudah expired
        \App\Models\Payment::where('payment_status', 'pending')
            ->whereHas('transaction', function ($query) {
                $query->where('expired_at', '<', now());
            })
            ->update(['payment_status' => 'failed']);

        // 2. Otomatis update juga status TRANSACTION-nya menjadi failed agar sinkron
        \App\Models\Transaction::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->update(['payment_status' => 'failed']);

        // 3. Ambil data payment terbaru untuk tabel admin
        $payments = \App\Models\Payment::latest()->get();

        return view('admin.payments.index', compact('payments')); // sesuaikan view adminmu
    }


    public function show(string $id)
    {
        $payment = Payment::with('transaction')->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}
