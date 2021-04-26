<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    // 返回所有的权限
    public function index () {
        return Permission::select('id', 'cn_name')->get();
    }
}
