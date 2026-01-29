<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureParticipant
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('participant_id')) {
            return redirect()->route('nik.form');
        }

        return $next($request);
    }
}
