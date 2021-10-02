<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class TwoFactorMiddleware
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
        if (!Auth::check()) {
            return abort(401);
        }
        $two_factor_methods = $request->user()->two_factor_methods()
            ->select(['id', 'google2fa_secret'])
            ->where('enabled', true)
            ->get();

        if ($two_factor_methods->isEmpty()) {
            return $next($request);
        }

        if ($request->session()->has('2fa_method')
            && $two_factor_methods->contains('id', $request->session()->get('2fa_method'))) {
            return $next($request);
        }

        return response(view('2fa.enter_otp')->with('user', $request->user()));
    }
}
