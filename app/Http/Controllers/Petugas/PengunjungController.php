<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class PengunjungController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])
        ->whereNotNull('used_at')
        ->latest()
        ->get();

        $adultCount = $transactions->sum(fn($t) => $t->booking->adult_qty);
        $studentCount = $transactions->sum(fn($t) => $t->booking->student_qty);
        $childCount = $transactions->sum(fn($t) => $t->booking->child_qty);

        $totalVisitors = $adultCount + $studentCount + $childCount;

        return view('petugas.pengunjung', compact(
            'transactions',
            'adultCount',
            'studentCount',
            'childCount',
            'totalVisitors'
        ));
    }
}
