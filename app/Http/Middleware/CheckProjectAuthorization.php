<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class CheckProjectAuthorization
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
        $project = Project::find($request->route('project')->id);
        if ($project == null) {
            abort(404);
        }
        if (Auth::user()->id != $project->user_id) {
            abort(404);
        }
        return $next($request);
    }
}
