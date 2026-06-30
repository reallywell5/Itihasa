<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\QrCode;
use App\Models\Transaction;

class DashboardWebController extends Controller
{
    public function index()
    {
        $totalMuseums = Museum::count();
        $totalTickets = Ticket::count();
        $totalPayments = Payment::count();
        $totalQrCodes = QrCode::count();

        $totalRevenue = Transaction::where('payment_status', 'paid')
            ->sum('total_amount');

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        $monthlyTransactions = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'data' => [
                Transaction::whereMonth('created_at', 1)->count(),
                Transaction::whereMonth('created_at', 2)->count(),
                Transaction::whereMonth('created_at', 3)->count(),
                Transaction::whereMonth('created_at', 4)->count(),
                Transaction::whereMonth('created_at', 5)->count(),
                Transaction::whereMonth('created_at', 6)->count(),
            ]
        ];

        return view('admin.dashboard', compact(
            'totalMuseums',
            'totalTickets',
            'totalPayments',
            'totalQrCodes',
            'totalRevenue',
            'recentTransactions',
            'monthlyTransactions'
        ));
    }
}
