<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\QrCode;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        // Sinkronisasi otomatis transaksi yang expired
        $expiredTransactions = Transaction::where('payment_status', 'pending')
            ->where('expired_at', '<', now())
            ->with(['booking.promo', 'payment'])
            ->get();

        foreach ($expiredTransactions as $etx) {
            $etx->update(['payment_status' => 'failed']);
            if ($etx->payment) {
                $etx->payment->update(['payment_status' => 'failed']);
            }
            if ($etx->booking && $etx->booking->status !== 'failed') {
                $etx->booking->update(['status' => 'failed']);
                if ($etx->booking->promo_id && $etx->booking->promo) {
                    $etx->booking->promo->decrement('used_count');
                }
            }
        }

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

        $availableTickets = Ticket::with('museum')
            ->when(! $isSuperAdmin, function ($q) use ($user) {
                $q->where('museum_id', $user->museum_id);
            })
            ->get();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalPaidTransactions' => $totalPaidTransactions,
            'availableTickets' => $availableTickets,
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
                return (int) ($t->scanned_pax ?: 1);
            }

            $paxFromQtys = (int) ($b->adult_qty ?? 0) + (int) ($b->student_qty ?? 0) + (int) ($b->child_qty ?? 0);

            return $paxFromQtys > 0 ? $paxFromQtys : (int) ($b->jumlah_anggota ?: ($t->scanned_pax ?: 1));
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

    /**
     * Penjualan Tiket Langsung di Loket Fisik / POS Walk-in (Fitur D Admin).
     */
    public function posStore(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'qty' => 'required|integer|min:1|max:100',
            'nama_penanggung_jawab' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:30',
            'payment_method' => 'required|in:tunai,cash,qris,debit',
        ], [
            'ticket_id.required' => 'Kategori tiket wajib dipilih.',
            'qty.required' => 'Jumlah tiket wajib diisi.',
            'qty.min' => 'Jumlah tiket minimal 1.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $ticket = Ticket::with('museum')->findOrFail($validated['ticket_id']);

        if (! $isSuperAdmin && $ticket->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak berhak menjual tiket untuk museum lain.');
        }

        $qty = (int) $validated['qty'];
        $total = $ticket->price * $qty;
        $today = Carbon::today()->toDateString();
        $namaPengunjung = ! empty(trim($validated['nama_penanggung_jawab'] ?? '')) ? trim($validated['nama_penanggung_jawab']) : 'Pengunjung Loket Fisik';
        $method = strtolower($validated['payment_method']) === 'cash' ? 'tunai' : strtolower($validated['payment_method']);

        $transaction = DB::transaction(function () use ($ticket, $qty, $total, $today, $namaPengunjung, $method, $validated, $user) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'museum_id' => $ticket->museum_id,
                'ticket_id' => $ticket->id,
                'nama_penanggung_jawab' => $namaPengunjung,
                'no_hp' => $validated['no_hp'] ?? '-',
                'jumlah_anggota' => $qty,
                'visit_date' => $today,
                'adult_qty' => $qty,
                'student_qty' => 0,
                'child_qty' => 0,
                'total_price' => $total,
                'status' => 'confirmed',
                'is_rombongan' => $qty >= 6,
                'ticket_summary' => json_encode([
                    [
                        'ticket_id' => $ticket->id,
                        'ticket_name' => $ticket->ticket_name,
                        'qty' => $qty,
                        'price' => (int) $ticket->price,
                    ],
                ]),
            ]);

            $tx = Transaction::create([
                'booking_id' => $booking->id,
                'invoice_code' => 'POS-'.strtoupper(Str::random(8)),
                'payment_method' => $method,
                'subtotal' => $total,
                'total_amount' => $total,
                'payment_status' => 'paid',
                'used_at' => now(),
                'scanned_by_user_id' => $user->id,
                'scanned_pax' => $qty,
                'gate_incident_tag' => 'Loket Fisik (Walk-in)',
                'gate_notes' => 'Pembelian langsung di loket kasir museum.',
            ]);

            Payment::create([
                'transaction_id' => $tx->id,
                'payment_method' => $method,
                'amount' => $total,
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            QrCode::create([
                'transaction_id' => $tx->id,
                'qr_code' => 'QR-'.$tx->invoice_code,
            ]);

            try {
                app(NotificationService::class)->notifyPaymentSuccess($tx);
            } catch (\Throwable $e) {
                \Log::warning('Gagal notifikasi payment POS: '.$e->getMessage());
            }

            return $tx;
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', "Tiket loket berhasil diterbitkan untuk {$qty} pengunjung (Invoice: {$transaction->invoice_code}). Transaksi otomatis tercatat pada Rekap Kas Harian.");
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
