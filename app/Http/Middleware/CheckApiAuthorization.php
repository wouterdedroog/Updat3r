<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;

class CheckApiAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        if ($request->bearerToken() == null) {
            return response()->json([
                'status' => '400',
                'message' => 'No API key provided!',
            ], 400);
        }
        $project = Project::where('name', '=', $request->project)->first();
        if ($project === null) {
            return response()->json([
                'status' => '400',
                'message' => 'Invalid project name provided.',
            ], 400);
        }
        if ($request->bearerToken() != $project->api_key) {
            return response()->json([
                'status' => '400',
                'message' => 'Invalid API key provided!',
            ], 400);
        }
        $request->merge(['project' => $project]);
        return $next($request);
    }
}
