<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Museum;
use App\Models\MuseumGallery;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $museumId = $user->museum_id;

        // Ambil Pengumuman / Broadcast Aktif untuk peran ini (Fitur E Super Admin)
        $activeBroadcasts = Broadcast::active()
            ->forRole($user->role)
            ->latest()
            ->get();

        // =========================================================
        // FITUR A: FILTER RENTANG WAKTU FLEKSIBEL SUPER ADMIN & ADMIN
        // =========================================================
        $period = $request->input('period', 'all');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        [$startDate, $endDate, $periodLabel] = $this->resolveDateRange($period, $customStart, $customEnd);

        if ($isSuperAdmin) {
            // ==========================================
            // STATISTIK REAL SUPER ADMIN (GLOBAL MONITORING DENGAN FILTER TANGGAL)
            // ==========================================
            $totalMuseums = Museum::count();
            $activeMuseumsCount = Museum::where('status', 'active')->count();
            $renovationMuseumsCount = Museum::where('status', 'renovation')->count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalStaff = User::where('role', 'staff')->count();
            $totalVisitors = User::where('role', 'visitor')->count();

            $baseTxQuery = Transaction::query()
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });

            $totalRevenue = (clone $baseTxQuery)->where('payment_status', 'paid')->sum('total_amount');
            $totalTransactions = (clone $baseTxQuery)->count();
            $paidTransactions = (clone $baseTxQuery)->where('payment_status', 'paid')->count();
            $pendingTransactions = (clone $baseTxQuery)->where('payment_status', 'pending')->count();

            $totalTickets = Ticket::count();
            $totalGalleries = MuseumGallery::count();
            $totalReviews = Review::count();
            $averageRating = Review::count() > 0 ? round((float) Review::avg('rating'), 1) : 0;
            $totalPayments = Payment::when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))->count();

            $totalQrCodes = Transaction::whereNotNull('used_at')
                ->when($startDate && $endDate, fn ($q) => $q->whereBetween('updated_at', [$startDate, $endDate]))
                ->count();

            // Breakdown komprehensif performa dan penanggung jawab per museum sesuai rentang tanggal
            $museumPerformances = Museum::with([
                'adminUser',
                'staffUsers',
            ])->withCount([
                'tickets',
                'galleries',
                'reviews',
            ])->get()->map(function ($museum) use ($startDate, $endDate) {
                $txQuery = Transaction::whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]));

                $revenue = (clone $txQuery)->where('payment_status', 'paid')->sum('total_amount');
                $txCount = (clone $txQuery)->count();
                $paidCount = (clone $txQuery)->where('payment_status', 'paid')->count();

                $visitors = Transaction::whereNotNull('used_at')
                    ->whereHas('booking', fn ($q) => $q->where('museum_id', $museum->id))
                    ->when($startDate && $endDate, fn ($q) => $q->whereBetween('updated_at', [$startDate, $endDate]))
                    ->count();

                $avgRating = $museum->reviews_count > 0 ? round((float) $museum->reviews()->avg('rating'), 1) : 0;
                $totalQuota = (int) $museum->tickets()->sum('slot');
                $occupancyRate = $totalQuota > 0 ? min(100, round(($visitors / $totalQuota) * 100, 1)) : 0;

                return [
                    'id' => $museum->id,
                    'name' => $museum->name,
                    'status' => $museum->status ?? 'active',
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
                ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
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
                'activeBroadcasts',
                'period',
                'periodLabel',
                'customStart',
                'customEnd',
                'totalMuseums',
                'activeMuseumsCount',
                'renovationMuseumsCount',
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
        $totalPayments = Payment::whereHas('transaction.booking', fn ($q) => $q->where('museum_id', $museumId))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->count();
        $successPayments = Payment::where('payment_status', 'paid')
            ->whereHas('transaction.booking', fn ($q) => $q->where('museum_id', $museumId))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->count();

        // 5. Menu Transaksi
        $baseAdminTxQuery = Transaction::whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]));

        $totalTransactions = (clone $baseAdminTxQuery)->count();
        $paidTransactions = (clone $baseAdminTxQuery)->where('payment_status', 'paid')->count();
        $pendingTransactions = (clone $baseAdminTxQuery)->where('payment_status', 'pending')->count();

        // 6. Pendapatan & Pengunjung Check-in (Scan QR)
        $totalRevenue = (clone $baseAdminTxQuery)->where('payment_status', 'paid')->sum('total_amount');
        $totalQrCodes = Transaction::whereNotNull('used_at')
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('updated_at', [$startDate, $endDate]))
            ->count();

        $recentTransactions = Transaction::with(['booking.user', 'booking.museum'])
            ->whereHas('booking', fn ($q) => $q->where('museum_id', $museumId))
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->latest()
            ->take(5)
            ->get();

        $monthlyTransactions = $this->getMonthlyTransactions($museumId);

        return view('admin.dashboard', compact(
            'isSuperAdmin',
            'activeBroadcasts',
            'period',
            'periodLabel',
            'customStart',
            'customEnd',
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
     * Resolusi rentang tanggal untuk filter analytics (Fitur A Super Admin).
     */
    private function resolveDateRange(string $period, ?string $customStart, ?string $customEnd): array
    {
        switch ($period) {
            case 'today':
                return [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay(),
                    'Hari Ini ('.Carbon::today()->translatedFormat('d M Y').')',
                ];
            case 'this_week':
                return [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                    'Minggu Ini ('.Carbon::now()->startOfWeek()->format('d M').' - '.Carbon::now()->endOfWeek()->format('d M Y').')',
                ];
            case 'this_month':
                return [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                    'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')',
                ];
            case 'this_year':
                return [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                    'Tahun Ini ('.Carbon::now()->year.')',
                ];
            case 'custom':
                if ($customStart && $customEnd) {
                    $start = Carbon::parse($customStart)->startOfDay();
                    $end = Carbon::parse($customEnd)->endOfDay();

                    return [
                        $start,
                        $end,
                        'Periode '.$start->translatedFormat('d M Y').' s/d '.$end->translatedFormat('d M Y'),
                    ];
                }

                return [null, null, 'Semua Waktu'];
            default:
                return [null, null, 'Semua Waktu'];
        }
    }

    /**
     * Ambil data transaksi 6 bulan terakhir (rolling).
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
