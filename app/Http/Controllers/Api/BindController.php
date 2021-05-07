<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BindController extends BaseController
{
    /**
     * 获取邮件的验证码
     */
    public function emailCode (Request $request) {
        $email = auth('api')->user()->email;
        // 发送验证码
        Mail::to($email)->send(new SendCode($email));
        return $this->response->array([
            "success" => true,
            "message" => "发送成功",
        ]);
    }

    // 更新邮箱
    public function updateEmail (Request $request) {
        $request->validate([
            'code' => 'required',
            'new-email' => 'required|email'
        ]);

        // 原来的旧邮箱
        $email = auth('api')->user()->email;

        // 更新邮箱
        if (cache('email_code_'.$email) != $request->input('code')) {
            return $this->response->array([
                'success' => false,
                'message' => '邮箱或验证码不正确'
            ])->setStatusCode(400);
        }

        $user = auth('api')->user();
        $user->email = $request->input('new-email');
        $user->save();
        return $this->response->array([
            "success" => true,
            "message" => "邮箱更改成功",
            "email" => $request->input('new-email')
        ]);
    }
}
