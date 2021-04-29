<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '邮箱不能为空',
            'password.required' => '密码不能为空'
        ];
    }
}
