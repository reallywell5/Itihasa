<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($id);

        return view('user.transaction.show', compact('transaction'));
    }

    public function ticket($id)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($id);

        return view('user.transaction.show2', compact('transaction'));
    }
}
