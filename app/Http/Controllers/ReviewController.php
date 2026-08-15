<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if ($booking->user_id !== $user->id) {
            abort(403, 'Booking ini bukan milik kamu.');
        }

        $hasVisited = $booking->transactions()
            ->where('payment_status', 'paid')
            ->whereNotNull('used_at')
            ->exists();

        if (! $hasVisited) {
            return back()->with('error', 'Kamu hanya bisa memberi review setelah tiket digunakan.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', 'Booking ini sudah pernah kamu review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => $user->id,
            'museum_id' => $booking->museum_id,
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih atas review kamu!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Ulasan ini bukan milik kamu.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }
}
