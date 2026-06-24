<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SuccessController extends Controller
{
    public function index(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $transaction = null;
        
        if ($bookingId) {
            $transaction = Transaction::with('museum')->where('booking_id', $bookingId)->first();
        }
        
        if (!$transaction) {
            // Data dummy untuk contoh
            $transaction = new Transaction();
            $transaction->booking_id = 'IT-29482';
            $transaction->name = 'John Doe';
            $transaction->date = now();
            $transaction->tickets = json_encode([
                ['name' => 'Adult', 'quantity' => 1, 'price' => 25.00, 'subtotal' => 25.00]
            ]);
            $transaction->total = 25.00;
            $transaction->qr_code = null;
            
            // Create dummy museum
            $museum = new \stdClass();
            $museum->name = 'National History Museum';
            $transaction->museum = $museum;
        }
        
        return view('success', compact('transaction'));
    }
}