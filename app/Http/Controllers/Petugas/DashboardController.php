<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $todayVisitors = Transaction::whereDate('created_at', Carbon::today())->count();

        $validQr = Transaction::whereNotNull('used_at')->count();

        $pendingTickets = Transaction::whereNull('used_at')->count();

        $recentTransactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])
        ->latest()
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
