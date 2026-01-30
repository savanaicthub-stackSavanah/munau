<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isStudent()) {
            return redirect()->route('login')->with('error', 'Student access required.');
        }

        return $next($request);
    }
}
