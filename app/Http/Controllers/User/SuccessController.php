<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SuccessController extends Controller
{
    public function index(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $transaction = null;

        if ($transactionId) {
            $transaction = Transaction::with([
                'booking.museum',
                'booking.user'
            ])->where('id', $transactionId)
              ->where('payment_status', 'paid')
              ->first();
        }

        if (!$transaction) {
            return redirect()->route('user.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('user.success', compact('transaction'));
    }
}
