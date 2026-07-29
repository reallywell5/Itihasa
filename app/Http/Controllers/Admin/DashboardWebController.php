<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardWebController extends Controller
{
    public function index()
    {
        $totalMuseums = Museum::count();
        $totalTickets = Ticket::count();
        $totalPayments = Payment::count();

        // Menggantikan QrCode::count() — dihitung dari transaksi yang sudah discan
        $totalQrCodes = Transaction::whereNotNull('used_at')->count();

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

        $monthlyTransactions = $this->getMonthlyTransactions();

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

    /**
     * Ambil data transaksi 6 bulan terakhir (rolling), dihitung dari bulan
     * berjalan mundur ke belakang, jadi selalu real-time mengikuti tanggal hari ini.
     */
    private function getMonthlyTransactions(): array
    {
        $labels = [];
        $data = [];

        // Mulai dari 5 bulan lalu sampai bulan ini (total 6 titik data)
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->translatedFormat('M Y'); // contoh: "Jun 2026"

            $data[] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
