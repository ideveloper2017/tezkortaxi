<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use  Auth;
use App;
use Config;

class CheckLanguage
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
        
		$locale =  Session::has('locale') ? Session::get('locale')  :  Config::get('app.locale');
		App::setLocale( $locale );
        

        return $next($request);
    }
}