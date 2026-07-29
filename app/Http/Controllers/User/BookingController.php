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
            'visit_date' => 'required|date|after_or_equal:today',
        ]);

        $museum->load('tickets');

        $total = 0;
        $ticketData = [];
        $adultQty = 0;
        $studentQty = 0;
        $childQty = 0;

        foreach ($museum->tickets as $ticket) {
            $qty = (int) $request->input('ticket_'.$ticket->id, 0);

            if ($qty > 0) {
                $total += $qty * $ticket->price;

                $ticketData[] = [
                    'ticket_name' => $ticket->ticket_name,
                    'qty' => $qty,
                    'price' => (int) $ticket->price,
                ];

                $nameLower = strtolower($ticket->ticket_name);
                if (str_contains($nameLower, 'anak') || str_contains($nameLower, 'child')) {
                    $childQty += $qty;
                } elseif (str_contains($nameLower, 'pelajar') || str_contains($nameLower, 'mahasiswa') || str_contains($nameLower, 'student')) {
                    $studentQty += $qty;
                } else {
                    $adultQty += $qty;
                }
            }
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'museum_id' => $museum->id,
            'visit_date' => $request->visit_date,
            'adult_qty' => $adultQty,
            'student_qty' => $studentQty,
            'child_qty' => $childQty,
            'total_price' => $total,
            'ticket_summary' => json_encode($ticketData),
            'status' => 'pending',
        ]);

        return redirect()->route('user.payment', $booking->id);
    }
}
