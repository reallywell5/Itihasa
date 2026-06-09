<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionWebController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user');

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }
}
