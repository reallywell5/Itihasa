<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class TransactionController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user',
        ])->findOrFail($id);

        // Hanya user pemilik booking yang bisa akses
        if ($transaction->booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.transaction.show', compact('transaction'));
    }

    public function ticket($id)
    {
        $transaction = Transaction::with([
            'booking.museum',
            'booking.user',
        ])->findOrFail($id);

        // Hanya user pemilik booking yang bisa akses
        if ($transaction->booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya transaksi yang sudah paid yang bisa akses QR Code
        if ($transaction->payment_status !== 'paid') {
            return redirect()
                ->route('user.payment.show3', $transaction->id)
                ->with('error', 'Tiket hanya tersedia setelah pembayaran berhasil.');
        }

        return view('user.transaction.show2', compact('transaction'));
    }

    public function downloadTicket($id)
    {
        $transaction = Transaction::with([
            'booking.user',
            'booking.museum',
        ])->findOrFail($id);

        // Hanya user pemilik booking yang bisa akses
        if ($transaction->booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya transaksi yang sudah paid yang bisa download QR Code
        if ($transaction->payment_status !== 'paid') {
            return redirect()
                ->route('user.payment.show3', $transaction->id)
                ->with('error', 'Tiket hanya tersedia setelah pembayaran berhasil.');
        }

        $qrCode = QrCode::size(250)
            ->generate($transaction->invoice_code);

        $html = View::make(
            'user.transaction.download-ticket',
            compact('transaction', 'qrCode')
        )->render();

        $tempHtml = storage_path('app/ticket.html');

        File::put($tempHtml, $html);

        $image = storage_path(
            'app/public/ticket-'.$transaction->id.'.png'
        );

        $browsershot = Browsershot::html($html)
            ->windowSize(900, 1400)
            ->fullPage()
            ->deviceScaleFactor(2)
            ->showBackground()
            ->margins(0, 0, 0, 0);

        if (PHP_OS_FAMILY !== 'Windows' && file_exists('/usr/bin/chromium-browser')) {
            $browsershot->setChromePath('/usr/bin/chromium-browser');
        }

        $browsershot->save($image);

        return response()->download($image, 'Tiket-'.$transaction->invoice_code.'.png', [
            'Content-Type' => 'image/png',
        ]);
    }
}
