<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $transactionId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $transaction = Transaction::with('booking')->findOrFail($transactionId);

        Review::create([
            'user_id' => Auth::id(),
            'museum_id' => $transaction->museum_id,
            'transaction_id' => $transaction->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Review berhasil ditambahkan.');
    }
}
