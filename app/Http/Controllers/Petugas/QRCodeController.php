<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    /**
     * Menampilkan daftar tiket QR Code khusus untuk museum petugas.
     */
    public function index(Request $request)
    {
        $staffMuseumId = auth()->user()->museum_id;

        $baseCountQuery = Transaction::when($staffMuseumId, function ($q) use ($staffMuseumId) {
            $q->whereHas('booking', fn ($bq) => $bq->where('museum_id', $staffMuseumId));
        });

        $totalQr = (clone $baseCountQuery)->count();
        $usedQr = (clone $baseCountQuery)->whereNotNull('used_at')->count();
        $activeQr = (clone $baseCountQuery)->where('payment_status', 'paid')->whereNull('used_at')->count();

        $query = Transaction::with([
            'booking.user',
            'booking.museum',
        ])->when($staffMuseumId, function ($q) use ($staffMuseumId) {
            $q->whereHas('booking', fn ($bq) => $bq->where('museum_id', $staffMuseumId));
        });

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                    ->orWhereHas('booking.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->whereNotNull('used_at');
            } elseif ($request->status === 'active') {
                $query->where('payment_status', 'paid')->whereNull('used_at');
            } elseif ($request->status === 'unpaid') {
                $query->where('payment_status', '!=', 'paid');
            }
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        return view('petugas.qrcodes.index', compact(
            'transactions',
            'totalQr',
            'usedQr',
            'activeQr'
        ));
    }

    /**
     * Menampilkan QR code satu tiket dalam ukuran besar.
     */
    public function show(Transaction $transaction)
    {
        $staffMuseumId = auth()->user()->museum_id;

        if ($staffMuseumId && $transaction->booking?->museum_id !== $staffMuseumId) {
            abort(403, 'Akses ditolak. Tiket ini bukan untuk museum Anda.');
        }

        $transaction->load(['booking.user', 'booking.museum']);

        return view('petugas.qrcodes.show', compact('transaction'));
    }
}
