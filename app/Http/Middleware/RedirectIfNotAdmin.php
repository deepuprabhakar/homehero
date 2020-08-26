<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->guest('/admin/login');
        }
        else if(!Auth::guard('admin')->user()->admin)
        {
            Auth::guard('admin')->logout();
            return redirect()->guest('/admin/login')->withErrors(['email' => "Admin privilege denied!"]);
        }
        else
            return $next($request);
    }
}
