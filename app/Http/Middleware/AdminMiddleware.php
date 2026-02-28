<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Verify that the user is authenticated and has admin privileges.
     * Uses user_status field (1 = active/admin).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->user_status != 1) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
