<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;

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
        // It would be better to return the status as an integer, not a string, and use proper error codes
        // like 403, but for the sake of compatibility it's better to keep it as-is.
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
        $request->merge(['project' => $project]);
        return $next($request);
    }
}
