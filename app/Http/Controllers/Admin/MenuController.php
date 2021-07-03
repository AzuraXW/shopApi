<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Models\MenuRole;
use App\Transformers\MenuTransformer;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class MenuController extends BaseController
{
    // 根据用户角色返回菜单
    public function index(Request $request)
    {
        $user = auth('admin')->user();
        // 角色下的菜单id，去除重复的菜单id
        $menu_ids = MenuRole::whereIn('rid', $user->roles->pluck('id'))->distinct()->pluck('mid');
        $menus = Menu::whereIn('id', $menu_ids)->where('pid', 0)->with([
            'children' => function ($query) use($menu_ids) {
                return $query->whereIn('id', $menu_ids);
            }
        ])->get();
        return $this->response->array($menus->toArray());
    }

    // 获取所有菜单列表
    public function list (Request $request) {
        $page = $request->query('page');
        $limit = $request->query('limit');
        // 分页查询
        $paginate = Menu::where('pid', 0)->paginate($limit);
        return $this->response->paginator($paginate, new MenuTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取菜单列表'
        ]);
    }

    public function componentCache (Menu $menu) {
        $menu->keep_alive = $menu->keep_alive === 0 ? 1 : 0;
        $menu->save();
        return $this->response->array([
            'success' => true,
            'message' => '更改成功',
            'keep_alive' => $menu->keep_alive
        ]);
    }

    // 给菜单分配角色以访问菜单
    public function giveRole (Request $request, Menu $menu) {
        // 可以访问该菜单的角色id集合
        $mid = $menu->id;
        $roleIds = MenuRole::where('mid', $mid)->pluck('rid');
        $assignRoleIds = $request->input('roleIds');
        $assignRoleIds = explode(',', $assignRoleIds);

        $needDel = [];
        $needAdd = [];
        foreach ($roleIds as $rid) {
            if (!in_array($rid, $assignRoleIds)) {
                array_push($needDel, $rid);
            }
        }
        foreach ($assignRoleIds as $rid) {
            if (!in_array($rid, $roleIds->toArray())) {
                array_push($needAdd, (int)$rid);
            }
        }
        MenuRole::where('mid', $mid)
            ->whereIn('rid', $needDel)
            ->delete();
        foreach ($needAdd as $addRid) {
            MenuRole::create([
                'mid' => $mid,
                'rid' => $addRid
            ]);
        }

        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }
}
