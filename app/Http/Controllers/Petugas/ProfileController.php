<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $petugas = Auth::user();

        $transactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])->latest()->get();

        $totalScan = $transactions->whereNotNull('used_at')->count();

        $validTickets = $transactions->whereNotNull('used_at')->count();

        $rejectedTickets = $transactions->where('payment_status', 'rejected')->count();

        $totalVisitors = $transactions
            ->whereNotNull('used_at')
            ->sum(function ($transaction) {
                return
                    $transaction->booking->adult_qty +
                    $transaction->booking->student_qty +
                    $transaction->booking->child_qty;
            });

        $recentActivities = $transactions->take(5);

        return view('petugas.profil', compact(
            'petugas',
            'totalScan',
            'validTickets',
            'rejectedTickets',
            'totalVisitors',
            'recentActivities'
        ));
    }
}
