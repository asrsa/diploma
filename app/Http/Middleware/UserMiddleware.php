<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class UserMiddleware
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
        //only user can access
        if(!$request->user()->isUser()) {
            return redirect(Config::get('paths.PATH_ROOT'))->withErrors(['error' => 'You cannot access this page']);
        }
        return $next($request);
    }
}
