<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;

class DashboardWebController extends Controller
{
    public function index()
    {
        $totalMuseums = Museum::count();

        $totalTickets = Ticket::count();

        $totalTransactions = Transaction::count();

        $totalUsers = User::count();

        $totalRevenue = Transaction::where('status', 'paid')
            ->sum('total_price');

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->paginate(5);

        return view('admin.dashboard', [
            'totalMuseums' => $totalMuseums,
            'totalTickets' => $totalTickets,
            'totalTransactions' => $totalTransactions,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
