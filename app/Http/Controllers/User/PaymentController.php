<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index($bookingId)
    {
        $booking = Booking::with('museum')->findOrFail($bookingId);

        return view('user.transaction.index', compact('booking'));
    }

    public function process(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $booking = Booking::findOrFail($bookingId);

        $serviceFee = 2000;

        $transaction = Transaction::create([
            'booking_id'      => $booking->id,
            'user_id'         => $booking->user_id,
            'museum_id'       => $booking->museum_id,
            'invoice_code'    => 'ITH-' . strtoupper(uniqid()),
            'payment_method'  => $request->payment_method,
            'subtotal'        => $booking->total_price,
            'service_fee'     => $serviceFee,
            'total_amount'    => $booking->total_price + $serviceFee,
            'payment_status'  => 'pending',
            'status'          => 'pending',
        ]);

        return redirect()->route('user.payment.show3', $transaction->id);
    }

    public function show3($transactionId)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($transactionId);

        return view('user.transaction.show3', compact('transaction'));
    }

    public function confirm($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        $transaction->update([
            'payment_status' => 'paid',
            'status' => 'paid'
        ]);

        $transaction->booking->update([
            'status' => 'paid'
        ]);

        return redirect()->route('user.transaction.show', $transaction->id);
    }
}
