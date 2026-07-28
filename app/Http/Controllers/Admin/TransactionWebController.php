<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionWebController extends Controller
{
    public function index()
    {
        Transaction::where('payment_status' , 'pending')
            ->where('expired_at', '<', now())
            ->update([
                'payment_status' => 'failed'
            ]);

        $transactions = Transaction::with('booking.user')
            ->latest()
            ->paginate(10);

        $totalRevenue = Transaction::where('payment_status', 'paid')
            ->sum('total_amount');

        $totalPaidTransactions = Transaction::where('payment_status', 'paid')
            ->count();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalPaidTransactions' => $totalPaidTransactions,
        ]);
    }

    public function show(Transaction $transaction)
    {
        if (
            $transaction->payment_status == 'pending' &&
            now()->greaterThan($transaction->expired_at)
        ) {
            $transaction->update([
                'payment_status' => 'failed'
            ]);

            $transaction->refresh();
        }

        $transaction->load('booking.user');

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }
}
