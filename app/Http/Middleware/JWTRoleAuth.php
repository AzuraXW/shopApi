<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRoleAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        try {
            // 解析token角色
            $tokenRole = $this->auth->parseToken()->getClaim('role');
        } catch (JWTException $e) {
            return $next($request);
        }

        if ($tokenRole != $role) {
            throw new UnauthorizedHttpException('jwt-auth', '用户角色错误');
        }
        return $next($request);
    }
}
