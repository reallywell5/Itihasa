<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'petugas') {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}