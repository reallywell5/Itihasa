<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;

class MuseumController extends Controller
{
    public function show($id)
    {
        $museum = Museum::with(['wishlists', 'galleries', 'tickets', 'reviews.user'])->findOrFail($id);

        // Extract city from address
        $city = '';
        if (preg_match('/(Jakarta|Bandung|Yogyakarta|Jogja|Solo|Surakarta|Surabaya|Semarang|Denpasar|Bali|Bogor|Malang|Medan|Padang|Palembang|Makassar)/i', $museum->address, $matches)) {
            $city = ucfirst(strtolower($matches[1]));
        }

        // Fetch nearby museums in same city or category
        $nearbyQuery = Museum::with(['tickets', 'wishlists'])
            ->where('id', '!=', $museum->id);

        if ($city) {
            $nearbyQuery->where('address', 'like', "%{$city}%");
        } else {
            $nearbyQuery->where('category', $museum->category);
        }

        $nearbyMuseums = $nearbyQuery->limit(3)->get();

        // If fewer than 2 nearby found, supplement with other museums
        if ($nearbyMuseums->count() < 2) {
            $supplement = Museum::with(['tickets', 'wishlists'])
                ->where('id', '!=', $museum->id)
                ->whereNotIn('id', $nearbyMuseums->pluck('id'))
                ->limit(3 - $nearbyMuseums->count())
                ->get();

            $nearbyMuseums = $nearbyMuseums->concat($supplement);
        }

        // Attach realistic distance & travel time estimation
        $distances = [
            0 => ['distance' => '1.2 km', 'time' => '~5 menit'],
            1 => ['distance' => '2.5 km', 'time' => '~10 menit'],
            2 => ['distance' => '3.8 km', 'time' => '~15 menit'],
        ];

        foreach ($nearbyMuseums as $idx => $nearby) {
            $distInfo = $distances[$idx % count($distances)];
            $nearby->trail_distance = $distInfo['distance'];
            $nearby->trail_time = $distInfo['time'];
            $nearby->trail_maps_url = 'https://www.google.com/maps/dir/?api=1&origin='.urlencode($museum->name.' '.$museum->address).'&destination='.urlencode($nearby->name.' '.$nearby->address);
        }

        $allReviews = $museum->reviews()->with('user')->latest()->get();

        return view('user.museum-detail', compact('museum', 'nearbyMuseums', 'allReviews'));
    }
}
