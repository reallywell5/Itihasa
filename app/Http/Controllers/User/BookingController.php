<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Museum $museum)
    {
        $museum->load('tickets');

        return view('user.booking', compact('museum'));
    }

    public function store(Request $request, Museum $museum)
    {
        $request->validate([
            'visit_date' => 'required|date',
        ]);

        $museum->load('tickets');

        $total = 0;
        $ticketData = [];

        foreach ($museum->tickets as $ticket) {
            $qty = $request->input('ticket_' . $ticket->id, 0);

            if ($qty > 0) {
                $total += $qty * $ticket->price;

                $ticketData[] = [
                    'ticket_name' => $ticket->ticket_name,
                    'qty' => $qty,
                    'price' => $ticket->price
                ];
            }
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'museum_id' => $museum->id,
            'visit_date' => $request->visit_date,
            'total_price' => $total,
            'ticket_summary' => json_encode($ticketData),
            'status' => 'pending'
        ]);

        return redirect()->route('user.payment', $booking->id);
    }
}
