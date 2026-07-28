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

        $recentTransactions = Transaction::with([
            'booking.user'
        ])
        ->latest()
        ->take(5)
        ->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = Transaction::query()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $i)
                ->count();
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = Transaction::query()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $i)
                ->count();
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = Transaction::query()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $i)
                ->count();
        }

        $monthlyTransactions = [
            'labels' => $labels,
            'data' => $data,
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
