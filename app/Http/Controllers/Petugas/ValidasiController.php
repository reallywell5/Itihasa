<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $transaction = null;

        if ($request->invoice_code) {
            $transaction = Transaction::with([
                'booking.user',
                'booking.museum'
            ])->where('invoice_code', $request->invoice_code)->first();
        }

        return view('petugas.validasi', compact('transaction'));
    }
}
