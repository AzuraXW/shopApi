<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // 更新用户头像
    public function updateAvatar (Request $request, Admin $user) {
        $path = $request->file('avatar')->store('avatars');
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }
        $user->update([
            'avatar' => $path
        ]);
        return $this->response->array([
            'success' => true,
            'message' => '头像更新成功',
            'avatar_url' => asset($path)
        ]);
    }

    // 用户更新个人信息
    public function updateProfile (Request $request, Admin $user) {
        $username = $request->input('username');
        $phone = $request->input('phone');
        if ($username == '' || $phone == '') {
            return $this->response->array([
                'success' => false,
                'message' => '缺少参数'
            ])->setStatusCode(403);
        }
        $user->update([
            'username' => $username,
            'phone' => $phone
        ]);
        return $this->response->array([
            'success' => true,
            'message' => '修改成功'
        ]);
    }

    // 用户个人信息
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
        } else {
            $response['avatar_url'] = asset($user->avatar);
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

    // 刷新token
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
