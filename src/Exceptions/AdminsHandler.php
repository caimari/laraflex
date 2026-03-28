<?php

namespace Caimari\LaraFlex\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionMembersHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Auth; 

class AdminsHandler extends ExceptionMembersHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
	
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
            
        if ($request->is('panel') || $request->is('panel/*')) {
            return redirect()->guest(route('login'));
        }
    
        if ($request->is('member') || $request->is('member/*')) {
            return redirect()->guest(route('member.login'));
        }
    
        return redirect()->guest(route('member.login'));
    }
}
