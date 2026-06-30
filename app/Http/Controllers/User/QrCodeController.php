<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class QrCodeController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user'
        ])->findOrFail($id);

        return view('user.transaction.show2', compact('transaction'));
    }
}
