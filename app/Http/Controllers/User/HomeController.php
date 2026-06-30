<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Museum::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $museums = $query->with('wishlists')->latest()->get();
        $recommended = Museum::withCount('wishlists')
            ->orderByDesc('wishlists_count')
            ->take(4)
            ->get();

        return view('user.home', compact('museums', 'recommended'));
    }
}
