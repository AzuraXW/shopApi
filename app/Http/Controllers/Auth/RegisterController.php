<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * 用户注册
     */
    public function store (RegisterRequest $request) {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('passwrod'));
        $user->save();
        return $this->response->array([
            'success' => true,
            'message' => '注册成功'
        ]);
    }
}
