<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index($bookingId)
    {
        $booking = Booking::with('museum')->findOrFail($bookingId);

        // Hanya pemilik booking yang boleh akses halaman pembayaran ini
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.transaction.index', compact('booking'));
    }

    public function process(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required'
        ]);

        $booking = Booking::findOrFail($bookingId);

        // Hanya pemilik booking yang boleh membuat transaksi dari booking ini
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction = Transaction::create([
            'booking_id'     => $booking->id,
            'invoice_code'   => 'ITH-' . strtoupper(uniqid()),
            'payment_method' => $request->payment_method,
            'subtotal'       => $booking->total_price,
            'total_amount'   => $booking->total_price,
            'payment_status' => 'pending',
            'expired_at'     => now()->addMinutes(15),
        ]);

        // Buat record Payment mengikuti transaksi yang baru dibuat
        Payment::create([
            'transaction_id' => $transaction->id,
            'payment_method' => $transaction->payment_method,
            'amount'         => $transaction->total_amount,
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('user.payment.show3', $transaction->id)
            ->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran melalui Midtrans sebelum waktu habis.')
            ->with('swal_success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran melalui Midtrans sebelum waktu habis.');
    }

    public function show3($transactionId)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($transactionId);

        // Hanya pemilik transaksi yang boleh melihat halaman pembayaran ini
        if ($transaction->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Cek apakah transaksi sudah lewat 15 menit
        if (
            $transaction->payment_status == 'pending' &&
            now()->greaterThan($transaction->expired_at)
        ) {

            $transaction->update([
                'payment_status' => 'failed',
            ]);

            $this->syncPaymentStatus($transaction, 'failed');

            return redirect()
                ->route('user.home')
                ->with('error', 'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.')
                ->with('swal_error', 'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.');
        }

        return view('user.transaction.show3', compact('transaction'));
    }

    public function confirm($transactionId)
    {
        $transaction = Transaction::with('booking')->findOrFail($transactionId);

        if ($transaction->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Jika sudah paid, langsung redirect ke halaman transaksi
        if ($transaction->payment_status == 'paid') {
            return redirect()
                ->route('user.transaction.show', $transaction->id)
                ->with('swal_info', 'Transaksi ini sudah dikonfirmasi sebelumnya.');
        }

        // Jika sudah failed, tolak
        if ($transaction->payment_status == 'failed') {
            return back()
                ->with('error', 'Transaksi sudah kedaluwarsa.')
                ->with('swal_error', 'Transaksi sudah kedaluwarsa.');
        }

        // Jika masih pending tapi sudah lewat expired_at, update ke failed
        if (now()->greaterThan($transaction->expired_at)) {
            $transaction->update([
                'payment_status' => 'failed',
            ]);

            $this->syncPaymentStatus($transaction, 'failed');

            return back()
                ->with('error', 'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.')
                ->with('swal_error', 'Waktu pembayaran telah habis. Silakan lakukan pemesanan kembali.');
        }

        $transaction->update([
            'payment_status' => 'paid',
        ]);

        $this->syncPaymentStatus($transaction, 'paid', now());

        return redirect()
            ->route('user.transaction.show', $transaction->id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi melalui Midtrans. Tiket QR kamu sudah aktif.')
            ->with('swal_success', 'Pembayaran berhasil dikonfirmasi melalui Midtrans. Tiket QR kamu sudah aktif.');
    }

    private function syncPaymentStatus(Transaction $transaction, string $status, $paidAt = null): void
    {
        $payment = Payment::where('transaction_id', $transaction->id)->latest()->first();

        if ($payment) {
            $payment->update([
                'payment_status' => $status,
                'paid_at'        => $paidAt,
            ]);
            return;
        }

        Payment::create([
            'transaction_id' => $transaction->id,
            'payment_method' => $transaction->payment_method,
            'amount'         => $transaction->total_amount,
            'payment_status' => $status,
            'paid_at'        => $paidAt,
        ]);
    }
}
