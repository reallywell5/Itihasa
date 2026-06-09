<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentWebController extends Controller
{
    public function index()
    {
        $payments = Payment::with('transaction')->latest()->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(string $id)
    {
        $payment = Payment::with('transaction')->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}
