<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        // 添加权限
        // 权限列表
        $permissions = [
            ['name' => 'users.index', 'cn_name' => '用户列表'],
            ['name' => 'users.show', 'cn_name' => '用户详情'],
            ['name' => 'users.lock', 'cn_name' => '用户禁用启用'],
            ['name' => 'category.status', 'cn_name' => '分类禁用启用'],
            ['name' => 'category.index', 'cn_name' => '分类列表'],
            ['name' => 'category.store', 'cn_name' => '添加分类'],
            ['name' => 'category.show', 'cn_name' => '分类详情'],
            ['name' => 'category.update', 'cn_name' => '更新分类'],
            ['name' => 'goods.status', 'cn_name' => '商品上架下架'],
            ['name' => 'goods.recommend', 'cn_name' => '商品推荐'],
            ['name' => 'goods.index', 'cn_name' => '商品列表'],
            ['name' => 'goods.store', 'cn_name' => '添加商品'],
            ['name' => 'goods.show', 'cn_name' => '商品详情'],
            ['name' => 'goods.update', 'cn_name' => '更新商品'],
            ['name' => 'comments.index', 'cn_name' => '评论列表'],
            ['name' => 'comments.show', 'cn_name' => '评论详情'],
            ['name' => 'comments.reply', 'cn_name' => '商家回复评论'],
            ['name' => 'orders.index', 'cn_name' => '订单列表'],
            ['name' => 'orders.show', 'cn_name' => '订单详情'],
            ['name' => 'orders.post', 'cn_name' => '订单发货'],
            ['name' => 'slides.index', 'cn_name' => '轮播图列表'],
            ['name' => 'slides.store', 'cn_name' => '添加轮播图'],
            ['name' => 'slides.show', 'cn_name' => '轮播图详情'],
            ['name' => 'slides.update', 'cn_name' => '更新轮播图'],
            ['name' => 'menus.index', 'cn_name' => '菜单列表'],
        ];
        foreach ($permissions as $p) {
            $arr = array_merge($p, ['guard_name' => 'admin']);
            Permission::create($arr);
        }
        // 添加角色
        $role = Role::create([
            'name' => 'super_admin',
            'cn_name' => '超级管理员',
            'guard_name' => 'admin',
            'description' => '超级管理员拥有最高权限，是该系统的完全管理者',
            'is_locked' => 1
        ]);
        // 为角色添加权限
        $role->givePermissionTo(Permission::all());
    }
}
