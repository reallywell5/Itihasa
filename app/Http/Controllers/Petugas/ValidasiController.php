<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;

class ValidasiController extends Controller
{
    public function index()
    {
        return view('petugas.validasi');
    }
}
