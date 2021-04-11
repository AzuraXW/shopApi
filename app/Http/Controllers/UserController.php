<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Transformers\UserTransformer;

class UserController extends BaseController
{
    // 获取用户信息
    public function userAll ($id) {
        $user = User::findOrFail($id);
        return $this->response->item($user, new UserTransformer)->setStatusCode(200);
        // return $this->response->array($user->toArray());
        // return $this->response->errorNotFound("This is an error");
        // throw new AccessDeniedHttpException('123456789');
    }

    
    public function login (Request $request) {
        $credentials = request(['email', 'password']);
        // dd(bcrypt($request->input('password')));
        if (!$token = auth('api')->attempt($credentials)) {
            throw new UnauthorizedHttpException('登录失败');
        }
        return $this->responseWithToken($token);
    }

    protected function responseWithToken($token) {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
