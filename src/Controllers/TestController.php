<?php

namespace Caimari\LaraFlex\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TestController extends Controller
{
    public function test()
    {
        return view('laraflex::admin.test.test');
    }

}
