<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CheckAvailableRole
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
        $roleId = $request->segments()[3];
        $is_locked = Role::where('id', $roleId)->first()['is_locked'];
        if ($is_locked == 1) {
            return response([
                'success' => false,
                'message' => '角色被锁定，操作无效'
            ])->setStatusCode('401');
        }
        return $next($request);
    }
}
