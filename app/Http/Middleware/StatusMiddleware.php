<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // MUST use client guard
        $user = Auth::guard('client')->user();

        if ($user && $user->status == 1) {
            return $next($request);
        }

        return redirect()->route('client.dashboard')
            ->with('error', 'Your account is not active.');
    }
}
