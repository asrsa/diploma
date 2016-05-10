<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class AdminMiddleware
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
        //only admin can access
        if(!$request->user()->isAdmin()) {
            return redirect(Config::get('paths.PATH_ROOT'))->withErrors(['error' => trans('errors.unathourized')]);
        }
        return $next($request);
    }
}
