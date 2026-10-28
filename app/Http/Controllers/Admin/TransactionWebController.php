<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        Transaction::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->update([
                'payment_status' => 'failed',
            ]);

        $transactionsQuery = Transaction::with(['booking.user', 'booking.museum'])
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_code', 'like', "%{$search}%")
                        ->orWhereHas('booking.user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('booking.museum', function ($mq) use ($search) {
                            $mq->where('name', 'like', "%{$search}%");
                        });
                });
            });

        $transactions = (clone $transactionsQuery)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $revenueQuery = Transaction::where('payment_status', 'paid')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            });

        $totalRevenue = (clone $revenueQuery)->sum('total_amount');
        $totalPaidTransactions = (clone $revenueQuery)->count();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalPaidTransactions' => $totalPaidTransactions,
        ]);
    }

    /**
     * Rekap Tutup Kas Harian (Fitur D Admin).
     */
    public function rekapKas(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $date = $request->input('date', Carbon::today()->toDateString());

        $query = Transaction::with(['booking.user', 'booking.museum', 'booking.promo'])
            ->where('payment_status', 'paid')
            ->whereDate('created_at', $date)
            ->when(! $isSuperAdmin, function ($q) use ($user) {
                $q->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            });

        $transactions = (clone $query)->orderBy('created_at', 'desc')->get();

        $totalCash = $transactions->filter(function ($t) {
            $m = strtolower($t->payment_method ?? '');

            return in_array($m, ['cash', 'tunai', 'loket', 'manual']);
        })->sum('total_amount');

        $totalDigital = $transactions->filter(function ($t) {
            $m = strtolower($t->payment_method ?? '');

            return ! in_array($m, ['cash', 'tunai', 'loket', 'manual']);
        })->sum('total_amount');

        $totalAmount = $transactions->sum('total_amount');
        $totalDiscounts = $transactions->sum(function ($t) {
            return $t->booking->discount_amount ?? 0;
        });

        $totalPax = $transactions->sum(function ($t) {
            $b = $t->booking;
            if (! $b) {
                return 0;
            }

            return ($b->adult_qty ?? 0) + ($b->student_qty ?? 0) + ($b->child_qty ?? 0);
        });

        return view('admin.transactions.rekap', compact(
            'transactions',
            'date',
            'totalCash',
            'totalDigital',
            'totalAmount',
            'totalDiscounts',
            'totalPax',
            'isSuperAdmin'
        ));
    }

    /**
     * Ekspor Laporan Transaksi ke format CSV (Fitur D Admin).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $date = $request->input('date', Carbon::today()->toDateString());

        $query = Transaction::with(['booking.user', 'booking.museum', 'booking.promo'])
            ->where('payment_status', 'paid')
            ->when($request->filled('date'), function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->when(! $isSuperAdmin, function ($q) use ($user) {
                $q->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('museum_id', $user->museum_id);
                });
            })
            ->orderBy('created_at', 'desc');

        $filename = 'rekap-transaksi-itihasa-'.$date.'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for proper Excel rendering
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No. Invoice',
                'Museum',
                'Nama Pengunjung',
                'No. HP',
                'Metode Pembayaran',
                'Subtotal (Rp)',
                'Diskon Promo (Rp)',
                'Total Bayar (Rp)',
                'Status',
                'Waktu Transaksi',
            ]);

            $query->chunk(100, function ($transactions) use ($handle) {
                foreach ($transactions as $tx) {
                    $booking = $tx->booking;
                    fputcsv($handle, [
                        $tx->invoice_code,
                        $booking->museum->name ?? '-',
                        $booking->nama_penanggung_jawab ?? ($booking->user->name ?? '-'),
                        $booking->no_hp ?? '-',
                        strtoupper($tx->payment_method ?? 'ONLINE'),
                        $tx->subtotal ?? $tx->total_amount,
                        $booking->discount_amount ?? 0,
                        $tx->total_amount,
                        strtoupper($tx->payment_status),
                        $tx->created_at?->format('Y-m-d H:i:s') ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        if (
            $transaction->payment_status === 'pending' &&
            $transaction->expired_at &&
            now()->greaterThan($transaction->expired_at)
        ) {
            $transaction->update([
                'payment_status' => 'failed',
            ]);

            $transaction->refresh();
        }

        $transaction->load(['booking.user', 'booking.museum']);

        if (! $user->isSuperAdmin() && $transaction->booking?->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi museum ini.');
        }

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }
}
