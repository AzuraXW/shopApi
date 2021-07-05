<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends BaseController
{
    /**
     * 获取邮件的验证码
     */
    public function emailCode (Request $request) {
        $email = $request->input('email');
        $isExist = User::where('email', $email)->count();
        if (!$isExist) {
            return $this->response->array([
                "success" => false,
                "message" => "没有该用户"
            ])->setStatusCode(400);
        }
        // 发送验证码
        Mail::to($email)->send(new SendCode($email));
        return $this->response->array([
            "success" => true,
            "message" => "发送成功"
        ]);
    }

    // 商城用户 通过邮箱找回密码
    public function updatePwdByEmail (Request $request) {
        $email = $request->input('email');
        $newPassword = $request->input('new-password');
        $code = $request->input('code');
        if (!$email || !$newPassword || !$code) {
            return $this->response->array([
                "success" => false,
                "message" => "缺少必要参数"
            ])->setStatusCode(422);
        }

        // 更新邮箱
        if (cache('email_code_'.$email) != $request->input('code')) {
            return $this->response->array([
                'success' => false,
                'message' => '邮箱或验证码不正确'
            ])->setStatusCode(400);
        }

        $user = User::where('email', $email)
            ->update([
                'password' => bcrypt($newPassword)
            ]);

        return $this->response->array([
            'success' => true,
            'message' => '密码更改成功'
        ]);
    }

    // 管理员 通过邮箱更改密码
    public function AdminUpdatePwdByEmail (Request $request, Admin $user) {
        $email = $user->email;
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $pwdConfirm = $request->input('pwd_confirm');
        $code = $request->input('code');
        // 必要参数检查
        if (!$email || !$newPassword || !$code || !$oldPassword || !$pwdConfirm) {
            return $this->response->array([
                "success" => false,
                "message" => "缺少必要参数"
            ])->setStatusCode(403);
        }
        // 验证当前密码是否正确
        if (!auth('admin')->attempt(['email' => $user->email, 'password' => $oldPassword])) {
            return $this->response->array([
                "success" => false,
                "message" => "旧密码不正确"
            ])->setStatusCode(400);
        }
        // 验证密码是否符合规范
        if (!preg_match('/\d+[a-zA-Z]+/', $newPassword)) {
            return $this->response->array([
                "success" => false,
                "message" => "密码应该由字母和数字组成"
            ])->setStatusCode(400);
        }
        // 新密码不能和就密码一致
        if ($oldPassword === $newPassword) {
            return $this->response->array([
                "success" => false,
                "message" => "新密码不能与旧密码一致"
            ])->setStatusCode(400);
        }

        // 两次密码是否一致
        if ($newPassword !== $pwdConfirm) {
            return $this->response->array([
                "success" => false,
                "message" => "两次密码不一致"
            ])->setStatusCode(400);
        }
        // 验证邮箱验证码是否正确
        if (cache('email_code_'.$email) != $request->input('code')) {
            return $this->response->array([
                'success' => false,
                'message' => '邮箱验证码不正确'
            ])->setStatusCode(400);
        }

        // 更新密码
        $user->update([
            'password' => bcrypt($newPassword)
        ]);

        return $this->response->array([
            'success' => true,
            'message' => '密码更新成功'
        ]);
    }
}
