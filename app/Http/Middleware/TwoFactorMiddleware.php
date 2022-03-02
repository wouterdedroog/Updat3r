<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $twoFactorMethods = $request->user()->twoFactorMethods()
            ->select(['id', 'google2fa_secret'])
            ->where('enabled', true)
            ->get();

        if ($twoFactorMethods->isEmpty()) {
            return $next($request);
        }

        if ($request->session()->has('2fa_method')
            && $twoFactorMethods->contains('id', $request->session()->get('2fa_method'))) {
            return $next($request);
        }

        return response(view('2fa.enter_otp')->with('user', $request->user()));
    }
}
