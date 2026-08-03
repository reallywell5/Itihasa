<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardWebController extends Controller
{
    public function index()
    {
        // ==== STATISTIK UTAMA (sesuai permintaan dosen) ====
        $totalMuseums = Museum::count();

        // Hanya hitung user dengan role "visitor" (pengunjung), bukan admin/staff
        $totalUsers = User::where('role', 'visitor')->count();

        $totalTransactions = Transaction::count();

        $totalRevenue = Transaction::where('payment_status', 'paid')
            ->sum('total_amount');

        // ==== STATISTIK TAMBAHAN (pendukung, sudah ada sebelumnya) ====
        $totalTickets = Ticket::count();
        $totalPayments = Payment::count();

        // Menggantikan QrCode::count() — dihitung dari transaksi yang sudah discan
        $totalQrCodes = Transaction::whereNotNull('used_at')->count();

        $recentTransactions = Transaction::with([
            'booking.user'
        ])
        ->latest()
        ->take(5)
        ->get();

        $monthlyTransactions = $this->getMonthlyTransactions();

        return view('admin.dashboard', compact(
            'totalMuseums',
            'totalUsers',
            'totalTransactions',
            'totalRevenue',
            'totalTickets',
            'totalPayments',
            'totalQrCodes',
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

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->translatedFormat('M Y');

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
