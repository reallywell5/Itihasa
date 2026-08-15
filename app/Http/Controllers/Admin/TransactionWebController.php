<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        Transaction::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->update([
                'payment_status' => 'failed',
            ]);

        $transactionsQuery = Transaction::with(['booking.user', 'booking.museum'])
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_code', 'like', "%{$search}%")
                        ->orWhereHas('booking.user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('booking.museum', function ($mq) use ($search) {
                            $mq->where('name', 'like', "%{$search}%");
                        });
                });
            });

        $transactions = (clone $transactionsQuery)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $revenueQuery = Transaction::where('payment_status', 'paid')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            });

        $totalRevenue = (clone $revenueQuery)->sum('total_amount');
        $totalPaidTransactions = (clone $revenueQuery)->count();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalPaidTransactions' => $totalPaidTransactions,
        ]);
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        if (
            $transaction->payment_status === 'pending' &&
            $transaction->expired_at &&
            now()->greaterThan($transaction->expired_at)
        ) {
            $transaction->update([
                'payment_status' => 'failed',
            ]);

            $transaction->refresh();
        }

        $transaction->load(['booking.user', 'booking.museum']);

        if (! $user->isSuperAdmin() && $transaction->booking?->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi museum ini.');
        }

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }
}
