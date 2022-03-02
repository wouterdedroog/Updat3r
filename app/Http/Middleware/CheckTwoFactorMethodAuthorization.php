<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTwoFactorMethodAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->twofactormethod == null
            || !Auth::check()
            || $request->twofactormethod->user_id != $request->user()->id) {
            abort(403);
        }

        return $next($request);
    }
}
