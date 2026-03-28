<?php

namespace Caimari\LaraFlex\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function acceptCookies()
    {
        // establecer la cookie 'cookie_consent' para un año
        $cookie = cookie('cookie_consent', 'accepted', 60 * 24 * 365);

        // Redirigir de vuelta a la página anterior con la cookie seteada
        return back()->withCookie($cookie);
    }
}
