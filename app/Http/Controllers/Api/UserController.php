<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    // 前台用户登录
    public function login (LoginRequest $request) {
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->response->array([
                'success' => false,
                'message' => '用户名或密码错误'
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

    // 用户详情
    public function show () {
        $user = auth('api')->user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    public function updatePwd (Request $request) {
        $valid = $request->validate([
            'old_password' => 'required|min:6|max:16',
            'password' => 'required|min:6|max:16|confirmed'
        ], [
            'old_password.required' => '旧密码不能为空',
            'old_password.min' => '旧密码最少6位',
            'old_password.max' => '旧密码最多16位'
        ]);
        $old_password = $request->input('old_password');
        $user = auth('api')->user();

        if (!password_verify($old_password, $user->password)) {
            return $this->response->array([
                'success' => false,
                'message' => '旧密码不正确'
            ])->setStatusCode(502);
        }
        $password = $request->input('password');
        if ($password === $old_password) {
            return $this->response->array([
                'success' => false,
                'message' => '新密码不能和旧密码相同'
            ])->setStatusCode(502);
        }
        $user->password = bcrypt($password);
        $user->save();
        auth('api')->logout();
        return $this->response->array([
            'success' => true,
            'message' => '密码更改成功'
        ]);
    }

    // 更换用户头像
    public function updateAvatar (Request $request) {
        $avatar = $request->input('avatar');
        if (!$avatar) {
            return $this->response->array([
               'success' => false,
               'message' => '缺少头像url地址'
            ])->setStatusCode(422);
        }

        $user = auth('api')->user();
        $user->avatar = $avatar;
        $user->save();
        return $this->response->array([
            'success' => true,
            'message' => '头像更换成功'
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
}
