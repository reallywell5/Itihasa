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

        $transactions = Transaction::with([
            'booking.museum',
            'booking'
        ])
        ->whereHas('booking', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest()
        ->get();

        $wishlists = Wishlist::with('museum')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalTransactions = $transactions->count();

        $totalVisitors = $transactions->sum(function ($transaction) {
            return
                ($transaction->booking->adult_qty ?? 0) +
                ($transaction->booking->student_qty ?? 0) +
                ($transaction->booking->child_qty ?? 0);
        });

        return view('user.profile', compact(
            'user',
            'transactions',
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

        // kalau password diisi, update password
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
