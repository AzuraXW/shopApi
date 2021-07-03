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

        if (auth('admin')->user()->is_locked === 1) {
            return $this->response->array([
                'success' => false,
                'message' => '该用户已被禁用,请联系管理员'
            ])->setStatusCode(403);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('admin')->user();
        if ($user->is_locked === 1) {
            return $this->response->array([
                'success' => false,
                'message' => '该用户已被禁用,请联系管理员'
            ])->setStatusCode(403);
        }
        $roles = $user->getRoleNames();
        $response = array_merge(
            $user->toArray(),
            [
                'roles' => $roles,
                'code' => 20000
            ]
        );
        if ($response['avatar_url'] == '') {
            $response['avatar_url'] = 'https://placeimg.com/80/80/any';
        }
        return $this->response->array($response);
    }

    public function logout()
    {
        auth('admin')->logout();

        return $this->response->array([
            'code' => 20000,
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
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'code' => 20000
        ]);
    }
}
