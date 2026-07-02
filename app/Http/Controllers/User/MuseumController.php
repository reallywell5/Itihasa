<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Museum;

class MuseumController extends Controller
{
    public function show($id)
    {
        $museum = Museum::findOrFail($id);

        return view('user.museum-detail', compact('museum'));
    }
}
