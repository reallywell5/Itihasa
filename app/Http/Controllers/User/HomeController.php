<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $allMuseums = Museum::with(['wishlists', 'tickets'])->get();

        // Ekstraksi daftar kota dari database
        $cities = $allMuseums->map(function ($m) {
            $addr = $m->address ?? '';
            if (preg_match('/(Jakarta|Bandung|Yogyakarta|Jogja|Solo|Surakarta|Surabaya|Semarang|Denpasar|Bali|Bogor|Malang|Medan|Padang|Palembang|Makassar)/i', $addr, $match)) {
                return ucfirst(strtolower($match[1]));
            }

            return trim(explode(',', $addr)[0] ?? '');
        })->filter()->unique()->values()->all();

        if (empty($cities)) {
            $cities = ['Bandung', 'Jakarta', 'Yogyakarta', 'Solo'];
        }

        $query = Museum::with(['wishlists', 'tickets'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('city'), function ($query) use ($request) {
                $city = $request->city;
                $query->where('address', 'like', "%{$city}%");
            });

        $museums = $query->orderBy('name')->get();

        // Filter Rentang Harga
        if ($request->filled('price_range')) {
            $range = $request->price_range;
            $museums = $museums->filter(function ($museum) use ($range) {
                $minPrice = $museum->tickets->min('price');
                if ($range === 'free') {
                    return $minPrice === null || $minPrice == 0;
                }
                if ($range === 'under_25k') {
                    return $minPrice !== null && $minPrice > 0 && $minPrice <= 25000;
                }
                if ($range === '25k_50k') {
                    return $minPrice !== null && $minPrice > 25000 && $minPrice <= 50000;
                }
                if ($range === 'above_50k') {
                    return $minPrice !== null && $minPrice > 50000;
                }

                return true;
            })->values();
        }

        $categories = [
            'museum' => 'Museum Sejarah',
            'seni' => 'Seni & Galeri',
            'budaya' => 'Budaya & Etnik',
            'alam' => 'Alam & Sains',
            'religius' => 'Religius',
        ];

        return view('user.home', compact('museums', 'cities', 'categories'));
    }
}
