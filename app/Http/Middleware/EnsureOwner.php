<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureOwner
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'owner') {
            return Redirect::to('/')->with('error', 'Unauthorized Access');
        }

        return $next($request);
    }
}
