<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        if ($request->bearerToken() == null) {
            return response()->json([
                'status' => '400',
                'message' => 'No API key provided!',
            ], 400);
        }

        $project = $request->project;
        if ($request->bearerToken() != $project->api_key) {
            return response()->json([
                'status' => '400',
                'message' => 'Invalid API key provided!',
            ], 400);
        }
        return $next($request);
    }
}
