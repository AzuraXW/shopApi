<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
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

    // 通过邮箱找回密码
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
}
