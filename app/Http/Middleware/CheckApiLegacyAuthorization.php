<?php

namespace App\Http\Middleware;

use Closure;
use App\Project;

class CheckApiLegacyAuthorization
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

        if ($request->key == null) {
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
        if ($project->legacy_api_key == null) {
            return response()->json([
                'status' => '400',
                'message' => 'This project does not have a legacy API token.',
            ], 400);
        }
        if ($project->legacy_api_key != $request->key) {
            return response()->json([
                'status' => '400',
                'message' => 'Invalid API key provided!',
            ], 400);
        }
        return $next($request);
    }
}
