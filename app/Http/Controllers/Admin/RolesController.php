<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\RolesRequest;
use App\Transformers\RolesTransformer;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends BaseController
{
    // 返回所有的角色以及对应的权限
    public function index () {
        return $this->response->collection(Role::all(), new RolesTransformer())->setMeta([
            'success' => true,
            'message' => '获取成功'
        ]);
    }

    // 创建角色
    public function store (RolesRequest $request) {
        $name = $request->input('name');
        $cn_name = $request->input('cn_name');
        $role = Role::create(['name' => $name, 'cn_name' => $cn_name, 'guard_name' => 'admin']);
        if ($role) {
            return $this->response->array([
                'success' => true,
                'message' => '成功创建角色'
            ]);
        }
    }

    // 返回所有角色的列表
    public function list () {
        return Role::select('id', 'name', 'cn_name')->get()->toArray();
    }

    // 为角色添加权限
    public function addPermission (Request $request, Role $role) {
        $permissionIds = $request->input('permissionids');
        $isMatched = preg_match('/^\d+(,\d+)*$/', $permissionIds);
        if (!$isMatched) {
            return $this->response->array([
                'success' => false,
                'message' => '权限字符串格式错误'
            ])->setStatusCode(422);
        }

        // 分割权限字符串为数组
        $permissionArrary = explode(',', $permissionIds);
        $role->givePermissionTo(Permission::whereIn('id', $permissionArrary)->get());
        return $this->response->array([
            'success' => true,
            'message' => '成功分配权限'
        ]);
    }

    public function revokePermission (Request $request, Role $role) {
        $permissionIds = $request->input('permissionids');
        $isMatched = preg_match('/^\d+(,\d+)*$/', $permissionIds);
        if (!$isMatched) {
            return $this->response->array([
                'success' => false,
                'message' => '权限字符串格式错误'
            ])->setStatusCode(422);
        }
        $permissionArrary = explode(',', $permissionIds);
        $role->revokePermissionTo(Permission::whereIn('id', $permissionArrary)->get());
        return $this->response->array([
            'success' => true,
            'message' => '成功移除权限'
        ]);
    }
}
