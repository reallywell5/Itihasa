<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;

class MuseumController extends Controller
{
    public function show($id)
    {
        $museum = Museum::with([
            'reviews.user'
        ])->findOrFail($id);

        $averageRating = round($museum->reviews()->avg('rating') ?? 0, 1);
        $totalReviews = $museum->reviews()->count();

        return view('user.museum-detail', compact(
            'museum',
            'averageRating',
            'totalReviews'
        ));
    }
}
