<?php
namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

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
    public function message () {
        /* return [
            'name.required' => '用户名不能为空'
        ]; */
    }
}
