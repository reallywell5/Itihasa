<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $todayVisitors = Transaction::whereDate('used_at', Carbon::today())->count();

        $validQr = Transaction::whereNotNull('used_at')->count();

        $pendingTickets = Transaction::where('payment_status', 'paid')
            ->whereNull('used_at')
            ->count();

        $recentTransactions = Transaction::with([
            'booking.user',
            'booking.museum',
        ])
            ->whereNotNull('used_at')
            ->orderByDesc('used_at')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'todayVisitors',
            'validQr',
            'pendingTickets',
            'recentTransactions'
        ));
    }
}
