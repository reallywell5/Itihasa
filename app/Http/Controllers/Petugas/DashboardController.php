<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $staffMuseumId = auth()->user()->museum_id;

        $baseQuery = Transaction::when($staffMuseumId, function ($query) use ($staffMuseumId) {
            $query->whereHas('booking', function ($bq) use ($staffMuseumId) {
                $bq->where('museum_id', $staffMuseumId);
            });
        });

        // Akumulasi total fisik pengunjung hari ini (termasuk tiket rombongan & kasir POS)
        $todayVisitors = (int) (clone $baseQuery)
            ->whereDate('updated_at', Carbon::today())
            ->where(function ($q) {
                $q->whereNotNull('used_at')
                    ->orWhere('scanned_pax', '>', 0);
            })
            ->sum(DB::raw('COALESCE(NULLIF(scanned_pax, 0), 1)'));

        // Tiket yang lunas dan siap dipindai di gate
        $validQr = (clone $baseQuery)
            ->where('payment_status', 'paid')
            ->whereNull('used_at')
            ->count();

        // Tiket belum lunas / pending
        $pendingTickets = (clone $baseQuery)
            ->where('payment_status', '!=', 'paid')
            ->count();

        $recentTransactions = (clone $baseQuery)->with([
            'booking.user',
            'booking.museum',
        ])
            ->whereNotNull('used_at')
            ->orderByDesc('used_at')
            ->take(5)
            ->get();

        $activeBroadcasts = Broadcast::active()
            ->forRole('staff')
            ->latest()
            ->get();

        return view('petugas.dashboard', compact(
            'todayVisitors',
            'validQr',
            'pendingTickets',
            'recentTransactions',
            'activeBroadcasts'
        ));
    }
}
