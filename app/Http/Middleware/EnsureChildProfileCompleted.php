<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureChildProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->role === 'child' && ! $user->hasCompletedChildProfile()) {
            if (! $request->routeIs('child.profile.complete') && ! $request->routeIs('child.profile.store')) {
                return redirect()->route('child.profile.complete');
            }
        }

        return $next($request);
    }
}