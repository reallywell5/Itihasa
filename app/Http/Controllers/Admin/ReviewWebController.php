<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
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

    /**
     * Submit or update an official admin reply to a review.
     */
    public function reply(Request $request, Review $review)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Hanya Admin Museum yang dapat memberikan tanggapan resmi atas ulasan museumnya.');
        }

        if ($review->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menanggapi ulasan museum ini.');
        }

        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ], [
            'admin_reply.required' => 'Tanggapan resmi wajib diisi.',
            'admin_reply.max' => 'Tanggapan maksimal 1000 karakter.',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'admin_replied_at' => now(),
        ]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Tanggapan resmi berhasil dipublikasikan.');
    }

    /**
     * Delete an official admin reply.
     */
    public function destroyReply(Review $review)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $review->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus tanggapan ulasan ini.');
        }

        $review->update([
            'admin_reply' => null,
            'admin_replied_at' => null,
        ]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Tanggapan resmi berhasil dihapus.');
    }

    public function destroy(Review $review)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki hak pemantauan. Penghapusan ulasan merupakan wewenang Admin Museum.');
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
