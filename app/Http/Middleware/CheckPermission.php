<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
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
        // 验证用户是否具有请求权限
        $user = auth('admin')->user();
        if (!$user->can($request->route()->getName())) {
            return response([
                'success' => false,
                'message' => '您没有权限操作'
            ])->setStatusCode(403);
        }
        return $next($request);
    }
}
