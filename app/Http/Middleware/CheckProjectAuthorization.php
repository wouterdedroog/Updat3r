<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProjectAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->id != $request->project->user_id) {
            abort(404);
        }
        if ($request->has('update') && $request->update->project_id != $request->project->id) {
            abort(404);
        }

        return $next($request);
    }
}
