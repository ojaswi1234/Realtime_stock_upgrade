<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->user()->role === 'super_admin') {
            return $next($request);
        }

        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
