<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;

class PengunjungController extends Controller
{
    public function index()
    {
        return view('petugas.pengunjung');
    }
}
