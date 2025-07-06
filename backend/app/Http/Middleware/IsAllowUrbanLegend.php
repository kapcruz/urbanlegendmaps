<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAllowUrbanLegend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = env('API_SECRET_KEY');
        $clientToken = $request->header('Authorization');

        if (!$token || $clientToken !== 'Bearer ' . $token) {
            return response()->json(['message' => 'Unauthorized: Invalid token.'], 403);
        }

        return $next($request);
    }
}
