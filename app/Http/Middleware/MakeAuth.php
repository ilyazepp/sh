<?php

namespace SHammer\Http\Middleware;

use Closure;
use SHammer\User;

class MakeAuth
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
        if (User::checkAuth($request) !== true) {
            return \Response::json(
                [
                    'code'      =>  401,
                    'message'   =>  '401 Unauthorized'
                ], 401);
        }

        return $next($request);
    }
}
