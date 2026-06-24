<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $museumId = $request->query('museum');
        $museum = null;
        $tickets = collect();
        
        if ($museumId) {
            $museum = Museum::find($museumId);
            if ($museum) {
                // Ambil tiket untuk museum tertentu, tanpa kondisi status
                $tickets = Ticket::where('museum_id', $museum->id)
                    ->where('slot', '>', 0)
                    ->get();
            }
        } else {
            $tickets = Ticket::with('museum')
                ->where('slot', '>', 0)
                ->get();
        }
        
        return view('booking', compact('museum', 'tickets'));
    }

    // ... metode store tetap sama
}