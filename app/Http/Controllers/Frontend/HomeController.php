<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $museums = Museum::all(); // Sudah benar
        return view('home', compact('museums'));
    }
}