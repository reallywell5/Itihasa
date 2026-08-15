<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $staffMuseumId = auth()->user()->museum_id;

        $baseQuery = Transaction::when($staffMuseumId, function ($query) use ($staffMuseumId) {
            $query->whereHas('booking', function ($bq) use ($staffMuseumId) {
                $bq->where('museum_id', $staffMuseumId);
            });
        });

        $todayVisitors = (clone $baseQuery)->whereDate('used_at', Carbon::today())->count();
        $validQr = (clone $baseQuery)->whereNotNull('used_at')->count();
        $pendingTickets = (clone $baseQuery)->where('payment_status', 'paid')
            ->whereNull('used_at')
            ->count();

        $recentTransactions = (clone $baseQuery)->with([
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
