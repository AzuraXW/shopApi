<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class authJWT extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        if (!$token = $this->auth->setRequest($request)->getToken()) {
            $this->expreturn();
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (JWTException $e) {
            $this->expreturn();
            $this->events->fire('tymon.jwt.valid', $user);

            return $next($request);
        }
    }

    private function expreturn()
    {
        $response = new Response();
        $response->headers->set('Accept', 'application/json');
        $response->setContent(json_encode([
            'code' => 401,
            'msg' => 'token验证过期'
        ]));
        $response->setStatusCode(401);
        $response->send();
        exit();
    }
}
