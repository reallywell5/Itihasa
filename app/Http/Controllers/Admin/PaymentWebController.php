<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        // 1. Sinkronisasi otomatis transaksi yang sudah melewati batas waktu (expired)
        $expiredTransactions = Transaction::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->with(['booking.promo', 'payment'])
            ->get();

        foreach ($expiredTransactions as $etx) {
            $etx->update(['payment_status' => 'failed']);
            if ($etx->payment) {
                $etx->payment->update(['payment_status' => 'failed']);
            }
            if ($etx->booking && $etx->booking->status !== 'failed') {
                $etx->booking->update(['status' => 'failed']);
                if ($etx->booking->promo_id && $etx->booking->promo) {
                    $etx->booking->promo->decrement('used_count');
                }
            }
        }

        // 3. Ambil data payment terbaru untuk tabel admin
        $payments = Payment::with(['transaction.booking.user', 'transaction.booking.museum'])
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->whereHas('transaction.booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('payment_method', 'like', "%{$search}%")
                        ->orWhereHas('transaction', function ($tq) use ($search) {
                            $tq->where('invoice_code', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();

        $payment->load(['transaction.booking.user', 'transaction.booking.museum']);

        if (! $user->isSuperAdmin() && $payment->transaction?->booking?->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke data pembayaran ini.');
        }

        return view('admin.payments.show', compact('payment'));
    }
}
