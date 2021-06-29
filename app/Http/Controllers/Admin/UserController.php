<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AddUserRequest;
use App\Models\Admin;
use App\Transformers\AdminTransformer;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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
        $username = $request->query('username');
        $email = $request->query('email');
        // 分页查询
        $paginate = Admin::when($username, function ($query) use($username) {
            return $query->where('username', 'like', "%$username%");
        })->when($email, function ($query) use($email) {
            return $query->where('email', 'like', "%$email%");
        })->paginate($limit);
        return $this->response->paginator($paginate, new AdminTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取用户列表'
        ]);
    }

    // 用户锁定
    public function lock (Admin $user) {
        $user->is_locked = $user->is_locked === 0 ? 1 : 0;
        $user->save();
        return $this->response->array([
            'success' => 200,
            'message' => '用户状态更新成功',
            'is_lock' => $user->is_locked
        ]);
    }

    /**
     * 添加管理员用户
     */
    public function store(AddUserRequest $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('passwrod');
        Admin::create([
            'username' => $username,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
        return $this->response->array([
            'success' => true,
            'message' => '成功添加后台用户'
        ]);
    }

    /**
     * 单个用户详情.
     *
     */
    public function show(Admin $user)
    {
        // 返回用户
        return $this->response->item($user, new AdminTransformer());
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

    // 为用户分配角色（继承权限）
    public function giveRole (Request $request, Admin $user) {
        $roleIds = $request->input('roleids');
        return $this->giveOrRemoveRole($roleIds, $user, true);
    }
    // 移除用户的角色
    public function removeRole (Request $request, Admin $user) {
        $roleIds = $request->input('roleids');
        return $this->giveOrRemoveRole($roleIds, $user, false);
    }

    protected function giveOrRemoveRole ($roleIds, $user, $flag) {
        $isMatched = preg_match('/^\d+(,\d+)*$/', $roleIds);
        if (!$isMatched) {
            return $this->response->array([
                'success' => false,
                'message' => '角色ID不符合格式'
            ])->setStatusCode(422);
        }
        $roleArray = explode(',', $roleIds);
        $roleNames = Role::whereIn('id', $roleArray)->pluck('name');

        if ($flag) {
            // 添加角色
            $user->assignRole($roleNames);
            return $this->response->array([
                'success' => true,
                'message' => '成功分配角色',
                'rolename' => $roleNames
            ]);
        } else {
            // 移除角色
            $user->removeRole($roleNames[0]);
            return $this->response->array([
                'success' => true,
                'message' => '成功移除角色'
            ]);
        }
    }

    // 为用户直接添加权限
    public function givePermission (Request $request, Admin $user) {
        $permissionIds = $request->input('permissionids');
        return $this->giveOrRevokePermission($permissionIds, $user, true);
    }

    // 撤销用户的直接权限
    public function revokePermission (Request $request, Admin $user) {
        $permissionIds = $request->input('permissionids');
        return $this->giveOrRevokePermission($permissionIds, $user, false);
    }

    // 添加或删除权限
    protected function giveOrRevokePermission ($permissionIds, $user, $flag) {
        $isMatched = preg_match('/^\d+(,\d+)*$/', $permissionIds);
        if (!$isMatched) {
            return $this->response->array([
                'success' => false,
                'message' => '权限ID不符合格式'
            ])->setStatusCode(422);
        }

        $permissionArrary = explode(',', $permissionIds);
        if ($flag) {
            // 添加权限
            $user->givePermissionTo(Permission::whereIn('id', $permissionArrary)->get());
            return $this->response->array([
                'success' => true,
                'message' => '成功分配权限'
            ]);
        } else {
            // 删除权限
            $user->revokePermissionTo(Permission::whereIn('id', $permissionArrary)->get());
            return $this->response->array([
                'success' => true,
                'message' => '成功撤销权限'
            ]);
        }

    }
}
