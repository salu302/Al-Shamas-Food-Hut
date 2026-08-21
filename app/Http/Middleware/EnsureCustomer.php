<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'customer') {
            return Redirect::to('/')->with('error', 'Unauthorized Access');
        }

        return $next($request);
    }
}
