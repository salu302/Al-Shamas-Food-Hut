<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureOwnerOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return Redirect::to('/')->with('error', 'Unauthorized Access');
        }

        if (! in_array($user->role, ['owner', 'super_admin', 'admin'])) {
            // If customer, redirect to my-orders; otherwise home
            if ($user->role === 'customer') {
                return Redirect::to('/my-orders')->with('error', 'Unauthorized Access');
            }

            return Redirect::to('/')->with('error', 'Unauthorized Access');
        }

        return $next($request);
    }
}
