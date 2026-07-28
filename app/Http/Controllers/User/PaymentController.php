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

        $transaction = Transaction::create([
            'booking_id'     => $booking->id,
            'invoice_code'   => 'ITH-' . strtoupper(uniqid()),
            'payment_method' => $request->payment_method,
            'subtotal'       => $booking->total_price,
            'total_amount'   => $booking->total_price,
            'payment_status' => 'pending',
            'expired_at'     => now()->addMinutes(15),
        ]);

        return redirect()->route('user.payment.show3', $transaction->id);
    }

    public function show3($transactionId)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($transactionId);

        // Cek apakah transaksi sudah lewat 15 menit
        if (
            $transaction->payment_status == 'pending' &&
            now()->greaterThan($transaction->expired_at)
        ) {

            $transaction->update([
                'payment_status' => 'failed',
            ]);

            return redirect()
                ->route('user.home')
                ->with('error', 'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.');
        }

        return view('user.transaction.show3', compact('transaction'));
    }

    public function confirm($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // Jika sudah paid, langsung redirect ke halaman transaksi
        if ($transaction->payment_status == 'paid') {
            return redirect()->route('user.transaction.show', $transaction->id);
        }

        // Jika sudah failed, tolak
        if ($transaction->payment_status == 'failed') {
            return back()->with(
                'error',
                'Transaksi sudah kedaluwarsa.'
            );
        }

        // Jika masih pending tapi sudah lewat expired_at, update ke failed
        if (now()->greaterThan($transaction->expired_at)) {
            $transaction->update([
                'payment_status' => 'failed',
            ]);

            return back()->with(
                'error',
                'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.'
            );
        }

        // Konfirmasi berhasil — ubah status ke paid
        $transaction->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->route('user.transaction.show', $transaction->id);
    }
}
