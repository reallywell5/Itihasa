<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\MuseumGallery;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $museumId = $user->museum_id;

        if ($isSuperAdmin) {
            // ==========================================
            // STATISTIK REAL SUPER ADMIN (GLOBAL MONITORING)
            // ==========================================
            $totalMuseums = Museum::count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalStaff = User::where('role', 'staff')->count();
            $totalVisitors = User::where('role', 'visitor')->count();

            $totalRevenue = Transaction::where('payment_status', 'paid')->sum('total_amount');
            $totalTransactions = Transaction::count();
            $paidTransactions = Transaction::where('payment_status', 'paid')->count();
            $pendingTransactions = Transaction::where('payment_status', 'pending')->count();

            $totalTickets = Ticket::count();
            $totalGalleries = MuseumGallery::count();
            $totalReviews = Review::count();
            $averageRating = Review::count() > 0 ? round((float) Review::avg('rating'), 1) : 0;
            $totalPayments = Payment::count();
            $totalQrCodes = Transaction::whereNotNull('used_at')->count();

            // Breakdown komprehensif performa dan penanggung jawab per museum
            $museumPerformances = Museum::with([
                'adminUser',
                'staffUsers',
            ])->withCount([
                'tickets',
                'galleries',
                'reviews',
            ])->get()->map(function ($museum) {
                $revenue = Transaction::where('payment_status', 'paid')
                    ->whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->sum('total_amount');

                $visitors = Transaction::whereNotNull('used_at')
                    ->whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->count();

                $txCount = Transaction::whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->count();

                $paidCount = Transaction::where('payment_status', 'paid')
                    ->whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->count();

                $avgRating = $museum->reviews_count > 0 ? round((float) $museum->reviews()->avg('rating'), 1) : 0;
                $totalQuota = (int) $museum->tickets()->sum('slot');
                $occupancyRate = $totalQuota > 0 ? min(100, round(($visitors / $totalQuota) * 100, 1)) : 0;

                return [
                    'id' => $museum->id,
                    'name' => $museum->name,
                    'address' => $museum->address,
                    'image' => $museum->image,
                    'admin_name' => $museum->adminUser?->name,
                    'admin_email' => $museum->adminUser?->email,
                    'admin_created_at' => $museum->adminUser?->created_at?->format('d M Y'),
                    'staff_count' => $museum->staffUsers->count(),
                    'tickets_count' => $museum->tickets_count,
                    'total_quota' => $totalQuota,
                    'occupancy_rate' => $occupancyRate,
                    'galleries_count' => $museum->galleries_count,
                    'reviews_count' => $museum->reviews_count,
                    'avg_rating' => $avgRating,
                    'transactions_count' => $txCount,
                    'paid_transactions_count' => $paidCount,
                    'visitors_count' => $visitors,
                    'revenue' => (int) $revenue,
                ];
            });

            $recentTransactions = Transaction::with(['booking.user', 'booking.museum'])
                ->latest()
                ->take(6)
                ->get();

            $monthlyTransactions = $this->getMonthlyTransactions(null);
            $monthlyRevenue = $this->getMonthlyRevenue(null);

            $museumRevenueDistribution = [
                'labels' => $museumPerformances->pluck('name')->toArray(),
                'data' => $museumPerformances->pluck('revenue')->toArray(),
            ];

            return view('admin.dashboard', compact(
                'isSuperAdmin',
                'totalMuseums',
                'totalAdmins',
                'totalStaff',
                'totalVisitors',
                'totalRevenue',
                'totalTransactions',
                'paidTransactions',
                'pendingTransactions',
                'totalTickets',
                'totalGalleries',
                'totalReviews',
                'averageRating',
                'totalPayments',
                'totalQrCodes',
                'museumPerformances',
                'recentTransactions',
                'monthlyTransactions',
                'monthlyRevenue',
                'museumRevenueDistribution'
            ));
        }

        // ==========================================
        // STATISTIK REAL ADMIN MUSEUM (SCOPED 100% REAL)
        // ==========================================
        $museum = $museumId ? Museum::find($museumId) : null;

        // 1. Menu Tiket
        $totalTickets = Ticket::where('museum_id', $museumId)->count();
        $totalQuota = (int) Ticket::where('museum_id', $museumId)->sum('slot');

        // 2. Menu Galeri
        $totalGalleries = MuseumGallery::where('museum_id', $museumId)->count();

        // 3. Menu Ulasan
        $totalReviews = Review::where('museum_id', $museumId)->count();
        $averageRating = $totalReviews > 0 ? round((float) Review::where('museum_id', $museumId)->avg('rating'), 1) : 0;

        // 4. Menu Pembayaran
        $totalPayments = Payment::whereHas('transaction.booking', fn ($q) => $q->where('museum_id', $museumId))->count();
        $successPayments = Payment::where('payment_status', 'paid')
            ->whereHas('transaction.booking', fn ($q) => $q->where('museum_id', $museumId))
            ->count();

        // 5. Menu Transaksi
        $totalTransactions = Transaction::whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))->count();
        $paidTransactions = Transaction::where('payment_status', 'paid')
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->count();
        $pendingTransactions = Transaction::where('payment_status', 'pending')
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->count();

        // 6. Pendapatan & Pengunjung Check-in (Scan QR)
        $totalRevenue = Transaction::where('payment_status', 'paid')
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->sum('total_amount');
        $totalQrCodes = Transaction::whereNotNull('used_at')
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->count();

        $recentTransactions = Transaction::with(['booking.user', 'booking.museum'])
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->latest()
            ->take(5)
            ->get();

        $monthlyTransactions = $this->getMonthlyTransactions($museumId);

        return view('admin.dashboard', compact(
            'isSuperAdmin',
            'museum',
            'totalTickets',
            'totalQuota',
            'totalGalleries',
            'totalReviews',
            'averageRating',
            'totalPayments',
            'successPayments',
            'totalTransactions',
            'paidTransactions',
            'pendingTransactions',
            'totalRevenue',
            'totalQrCodes',
            'recentTransactions',
            'monthlyTransactions'
        ));
    }

    /**
     * Ambil data transaksi 6 bulan terakhir (rolling), dihitung dari bulan
     * berjalan mundur ke belakang. Mendukung scoping museum_id untuk Admin Museum.
     */
    private function getMonthlyTransactions(?int $museumId = null): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->translatedFormat('M Y');

            $query = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);

            if ($museumId) {
                $query->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId));
            }

            $data[] = $query->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Ambil data tren omzet pendapatan 6 bulan terakhir (rolling).
     */
    private function getMonthlyRevenue(?int $museumId = null): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->translatedFormat('M Y');

            $query = Transaction::where('payment_status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);

            if ($museumId) {
                $query->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId));
            }

            $data[] = (int) $query->sum('total_amount');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
