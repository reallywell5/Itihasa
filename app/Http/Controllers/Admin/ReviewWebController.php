<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $reviews = Review::with(['user', 'museum'])
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->where('museum_id', $user->museum_id);
            })
            ->withCount('reports')
            ->orderByDesc('reports_count')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki hak pemantauan ulasan. Moderasi ulasan dilakukan oleh Admin Museum.');
        }

        if ($review->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus ulasan museum ini.');
        }

        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
