<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\View\View;

class PengunjungController extends Controller
{
    public function index(): View
    {
        $staffMuseumId = auth()->user()->museum_id;

        $transactions = Transaction::with([
            'booking.user',
            'booking.museum',
        ])
            ->where(function ($q) {
                $q->whereNotNull('used_at')
                    ->orWhere('scanned_pax', '>', 0);
            })
            ->when($staffMuseumId, function ($query) use ($staffMuseumId) {
                $query->whereHas('booking', function ($bq) use ($staffMuseumId) {
                    $bq->where('museum_id', $staffMuseumId);
                });
            })
            ->latest('updated_at')
            ->get();

        $adultCount = (int) $transactions->sum(fn ($t) => (int) ($t->booking->adult_qty ?? 0));
        $studentCount = (int) $transactions->sum(fn ($t) => (int) ($t->booking->student_qty ?? 0));
        $childCount = (int) $transactions->sum(fn ($t) => (int) ($t->booking->child_qty ?? 0));

        $totalPaxFromSummary = $adultCount + $studentCount + $childCount;
        $totalPaxFromScans = (int) $transactions->sum('scanned_pax');
        $totalVisitors = max($totalPaxFromSummary, $totalPaxFromScans, $transactions->count());

        return view('petugas.pengunjung', compact(
            'transactions',
            'adultCount',
            'studentCount',
            'childCount',
            'totalVisitors'
        ));
    }
}
