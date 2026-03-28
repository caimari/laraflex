<?php

namespace Caimari\LaraFlex\Middleware;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class AdminsRedirectIfAuthenticated
{
	
	    public function handle($request, Closure $next, $guard = null)
        
    {

            if ($guard === 'admin' && Auth::guard($guard)->check()) {
                return redirect()->route('panel.index');
            }
            
            if (Auth::guard($guard)->check()) {
                return redirect('/');
            }
            
                return $next($request);
    }
}
