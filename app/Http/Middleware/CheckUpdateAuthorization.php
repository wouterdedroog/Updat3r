<?php

namespace App\Http\Middleware;

use Closure;
use App\Project;
use App\Update;
use Illuminate\Support\Facades\Auth;

class CheckUpdateAuthorization
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
        //Store function
        if ($request->has('project_id')) {
            $project = Project::find($request['project_id']);
            if ($project == null) {
                abort(404);
            }
            if (Auth::user()->id != $project->user_id) {
                abort(404);
            }
            $request->merge(['project' => $project]);
            return $next($request);
        }

        //Update/destroy function
        $update = Update::find($request->route('update')->id);
        if ($update == null) {
            abort(404);
        }
        if (Auth::user()->id != $update->project->user_id) {
            abort(404);
        }
        return $next($request);
    }
}
