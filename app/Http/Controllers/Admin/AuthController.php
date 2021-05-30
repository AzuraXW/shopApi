<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class AuthController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return $this->response->array([
                'success' => false,
                'message' => '邮箱或密码错误'
            ])->setStatusCode(401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('admin')->user();
        return $user->getAllRoles();
//        foreach ($roles)
        return auth('admin')->user()->getAllPermissions();
        return $this->response->array(auth('admin')->user()->toArray());
    }

    public function logout()
    {
        auth('admin')->logout();

        return $this->response->array([
            'success' => true,
            'message' => '成功退出'
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
