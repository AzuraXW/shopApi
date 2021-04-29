<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('api')->user()->is_admin === 0) {
            return response([
                'success' => false,
                'message' => '您不是管理员'
            ])->setStatusCode(403);
        }
        return $next($request);
    }
}
