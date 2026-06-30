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
        return view('user.booking', compact('museum'));
    }

    public function store(Request $request, Museum $museum)
    {
        $request->validate([
            'visit_date' => 'required|date',
        ]);

        $adult = $request->adult_qty ?? 0;
        $student = $request->student_qty ?? 0;
        $child = $request->child_qty ?? 0;

        $total =
            ($adult * 25000) +
            ($student * 15000) +
            ($child * 10000);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'museum_id' => $museum->id, // FIX ambil dari route
            'visit_date' => $request->visit_date,
            'adult_qty' => $adult,
            'student_qty' => $student,
            'child_qty' => $child,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        return redirect()->route('user.payment', $booking->id);
    }
}
