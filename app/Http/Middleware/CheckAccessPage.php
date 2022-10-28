<?php

namespace App\Http\Middleware;

use Closure;
use  Auth;



class CheckAccessPage
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
		
        if ( Auth::check() || Auth::guard('admin')->check() || Auth::guard('account')->check() || Auth::guard('provider')->check() || Auth::guard('dispatcher')->check() || Auth::guard('fleet')->check()  ) {

			return redirect('/');
        }


        return $next($request);
    }
}