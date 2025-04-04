<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $age): Response
    {
        if ($request->age < $age) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'You are not authorized to access this resource'], 403);
        }

        return $next($request);
    }
}
