<?php
namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:16|confirmed'
        ];
    }

    // 错误消息自定义
    public function messages () {
        return [
            'name.required' => '用户名不能为空',
            'email.email' => '邮箱格式不正确',
            'password.required' => '密码不能为空',
            'password.min' => '密码应不少于6位',
            'password.max' => '密码应不大于16位',
            'password.confirmed' => '两次密码不一致',
            'email.unique' => '邮箱已经被注册过了',
            'name.max' => '用户名不应超过16位'
        ];
    }
}
