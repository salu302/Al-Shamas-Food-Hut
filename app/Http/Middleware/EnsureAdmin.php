<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            return Redirect::to('/')->with('error', 'Unauthorized Access');
        }

        return $next($request);
    }
}
