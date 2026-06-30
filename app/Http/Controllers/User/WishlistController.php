<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Museum;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('museum')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    public function store($museumId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('museum_id', $museumId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('success', 'Museum removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'museum_id' => $museumId
        ]);

        return back()->with('success', 'Museum added to wishlist.');
    }

    public function destroy($id)
    {
        Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Museum dihapus dari wishlist.');
    }
}
