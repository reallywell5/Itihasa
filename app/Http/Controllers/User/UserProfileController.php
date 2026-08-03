<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil transaksi paid yang booking-nya masih berstatus 'active'
        $activeTransactions = Transaction::with(['booking.museum'])
            ->whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'active');
            })
            ->where('payment_status', 'paid')
            ->get();

        // 2. Loop untuk mengecek apakah sudah melewati closing_time museum
        foreach ($activeTransactions as $transaction) {
            $booking = $transaction->booking;

            if ($booking && $booking->museum) {
                $closingDateTime = \Carbon\Carbon::parse($booking->visit_date . ' ' . $booking->museum->closing_time);

                if (now()->greaterThan($closingDateTime)) {
                    $booking->update(['status' => 'expired']);
                }
            }
        }

        // 3. Auto-gagalkan transaksi PENDING yang sudah lewat batas waktu bayar (expired_at),
        // supaya tidak nyangkut selamanya di daftar "Menunggu Pembayaran" kalau user
        // menutup aplikasi sebelum bayar dan tidak pernah buka lagi halaman show3.
        Transaction::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('payment_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->update(['payment_status' => 'failed']);

        // 4. Transaksi yang SUDAH DIBAYAR (riwayat kunjungan seperti sebelumnya)
        $transactions = Transaction::with([
            'booking.museum',
            'booking'
        ])
        ->whereHas('booking', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('payment_status', 'paid')
        ->latest()
        ->get();

        // 5. Transaksi yang MASIH PENDING (belum dibayar, masih dalam batas waktu),
        // supaya user yang ke-close aplikasinya bisa balik lagi lanjutkan pembayaran.
        $pendingTransactions = Transaction::with(['booking.museum'])
            ->whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('payment_status', 'pending')
            ->latest()
            ->get();

        $wishlists = Wishlist::with('museum')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalTransactions = $transactions->count();

        $totalVisitors = $transactions->sum(function ($transaction) {
            return ($transaction->booking->adult_qty ?? 0) +
                ($transaction->booking->student_qty ?? 0) +
                ($transaction->booking->child_qty ?? 0);
        });

        return view('user.profile', compact(
            'user',
            'transactions',
            'pendingTransactions',
            'wishlists',
            'totalTransactions',
            'totalVisitors'
        ));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('user.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('swal_success', 'Profil berhasil diperbarui.');
    }
}
