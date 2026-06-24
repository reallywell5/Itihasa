<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua museum tanpa filter 'status'
        $museums = Museum::all();
        
        return view('home', compact('museums'));
    }
}