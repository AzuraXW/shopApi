<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $limit = $request->query('limit');
        $name = $request->query('name');
        $email = $request->query('email');
        $is_admin = $request->query('is_admin');
        // 分页查询
        $paginate = User::when($is_admin != '', function ($query) use ($is_admin) {
            return $query->where('is_admin', $is_admin);
        })->when($name, function ($query) use($name) {
            return $query->where('name', 'like', "%$name%");
        })->when($email, function ($query) use($email) {
            return $query->where('email', 'like', "%$email%");
        })->paginate($limit);
        return $this->response->paginator($paginate, new UserTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取用户列表'
        ]);
    }

    // 用户锁定
    public function lock (User $user) {
        $user->is_locked = $user->is_locked === 0 ? 1 : 0;
        $user->save();
        return $this->response->array([
            'success' => 200,
            'message' => '用户状态更新成功',
            'is_lock' => $user->is_locked
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * 单个用户详情.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // 返回用户
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    // 为用户分配角色
    public function role (Request $request, User $user) {
        $roleIds = $request->input('roleids');
        $isMatched = preg_match('/^\d(,\d+)*$/', $roleIds);
        // 判断用户是否是管理员
        if (!$user->is_admin) {
            return $this->response->array([
                'success' => false,
                'message' => '该用户不是管理员'
            ])->setStatusCode(403);
        }
        if (!$isMatched) {
            return $this->response->array([
                'success' => false,
                'message' => '角色ID不符合格式'
            ])->setStatusCode(422);
        }
        $roleArray = explode(',', $roleIds);
        $roleNames = Role::whereIn('id', $roleArray)->pluck('name');
        $user->assignRole($roleNames);
        return $this->response->array([
            'success' => true,
            'message' => '成功分配角色',
            'rolename' => $roleNames
        ]);
    }
}
