<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewReportController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $user = auth()->user();

        if ($review->user_id === $user->id) {
            $msg = 'Kamu tidak bisa melaporkan ulasan sendiri.';

            return back()->with('error', $msg)->with('swal_error', $msg);
        }

        if ($review->reports()->where('user_id', $user->id)->exists()) {
            $msg = 'Kamu sudah pernah melaporkan ulasan ini sebelumnya.';

            return back()->with('error', $msg)->with('swal_error', $msg);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'Alasan laporan wajib diisi.',
        ]);

        $review->reports()->create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
        ]);

        $msg = 'Laporan berhasil dikirim. Terima kasih atas laporanmu.';

        return back()->with('success', $msg)->with('swal_success', $msg);
    }
}
