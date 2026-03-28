<?php

namespace Caimari\LaraFlex\Middleware;

use Closure;
use Illuminate\Http\Request;

class CookieConsent
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasCookie('cookie_consent')) {
            view()->share('show_cookie_consent_banner', true);
        }

        return $next($request);
    }
}
