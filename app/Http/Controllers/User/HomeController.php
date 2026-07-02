<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Museum::with('wishlists');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $museums = $query->orderBy('name')->get();

        return view('user.home', compact('museums'));
    }
}
