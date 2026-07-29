<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $petugas = Auth::user();

        $transactions = Transaction::with([
            'booking.user',
            'booking.museum'
        ])->latest()->get();

        $totalScan = $transactions->whereNotNull('used_at')->count();
        $validTickets = $transactions->whereNotNull('used_at')->count();
        $rejectedTickets = $transactions->where('payment_status', 'rejected')->count();

        $totalVisitors = $transactions
            ->whereNotNull('used_at')
            ->sum(function ($transaction) {
                return
                    ($transaction->booking->adult_qty ?? 0) +
                    ($transaction->booking->student_qty ?? 0) +
                    ($transaction->booking->child_qty ?? 0);
            });

        // Hanya tiket yang SUDAH discan, diurutkan dari yang paling baru discan
        $recentActivities = $transactions
            ->whereNotNull('used_at')
            ->sortByDesc('used_at')
            ->take(5);

        return view('petugas.profil', compact(
            'petugas',
            'totalScan',
            'validTickets',
            'rejectedTickets',
            'totalVisitors',
            'recentActivities'
        ));
    }

    public function edit()
    {
        $petugas = Auth::user();

        return view('petugas.profil-edit', compact('petugas'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('petugas.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
