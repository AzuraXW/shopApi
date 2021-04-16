<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginController extends BaseController
{
    // 用户登录
    public function login (Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = request(['email', 'password']);
        // dd(bcrypt($request->input('password')));
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->response->array([
                'success' => false,
                'message' => '登录失败'
            ])->setStatusCode(401);
        }
        // 被禁用的用户不能登录
        if (auth('api')->user()->is_locked === 1) {
            return $this->response->array([
                'success' => false,
                'message' => '该用户已被禁用'
            ])->setStatusCode(403);
        }
        // 登录成功
        return $this->responseWithToken($token, [
            'success' => true,
            'message' => '登录成功'
        ]);
    }


    // 格式化 成功登录后的响应
    protected function responseWithToken($token, $additionalRes) {
        $response = array_merge([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], $additionalRes);
        return $this->response->array($response);
    }

    // 用户退出登录
    public function logout()
    {
        auth('api')->logout();

        return $this->response->array([
            'success' => true,
            'message' => '成功退出登录'
        ]);
    }

    // 刷新token
    public function refresh()
    {
        return $this->responseWithToken(auth('api')->refresh(), [
            'success' => true,
            'message' => 'token刷新成功'
        ]);
    }

    // 返回当前用户信息
    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
