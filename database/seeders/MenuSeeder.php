<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $menus = [
            [
                'path' => '/users/admin',
                'component' => 'Layout',
                'redirect' => '/users/admin/list',
                'title' => '后台用户',
                'icon' => 'peoples',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'users/list',
                        'name' => 'AdminUserList',
                        'title' => '后台用户列表'
                    ],
                    [
                        'path' => 'add',
                        'component' => 'users/add',
                        'name' => 'AddAdminUser',
                        'title' => '添加后台用户'
                    ]
                ]
            ],
            [
                'path' => '/permission',
                'component' => 'Layout',
                'redirect' => '/permission/role',
                'title' => '权限管理',
                'icon' => 'lock',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'role',
                        'component' => 'permission/role',
                        'name' => 'PermissionRole',
                        'title' => '角色信息'
                    ]
                ]
            ],
            [
                'path' => '/goods',
                'component' => 'Layout',
                'redirect' => '/goods/list',
                'title' => '商品管理',
                'icon' => 'el-icon-goods',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'goods/list',
                        'name' => 'goodsList',
                        'title' => '商品列表'
                    ]
                ]
            ],
            [
                'path' => '/comment',
                'component' => 'Layout',
                'redirect' => '/comment/list',
                'title' => '评论管理',
                'icon' => 'el-icon-info',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'comment/list',
                        'name' => 'commentList',
                        'title' => '评论列表'
                    ]
                ]
            ],
            [
                'path' => '/order',
                'component' => 'Layout',
                'redirect' => '/order/list',
                'title' => '订单管理',
                'icon' => 'list',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'order/list',
                        'name' => 'orderList',
                        'title' => '订单列表'
                    ]
                ]
            ],
            [
                'path' => '/slide',
                'component' => 'Layout',
                'redirect' => '/slide/list',
                'title' => '轮播图管理',
                'icon' => 'el-icon-picture',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'slide/list',
                        'name' => 'slideList',
                        'title' => '轮播图列表'
                    ]
                ]
            ],
            [
                'path' => '/category',
                'component' => 'Layout',
                'redirect' => '/category/list',
                'title' => '分类管理',
                'icon' => 'tree',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'category/list',
                        'name' => 'categoryList',
                        'title' => '分类列表'
                    ]
                ]
            ],
            [
                'path' => '/shopuser',
                'component' => 'Layout',
                'redirect' => '/shopuser/list',
                'title' => '商城用户',
                'icon' => 'user',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'shopuser/list',
                        'name' => 'shopuserList',
                        'title' => '用户列表'
                    ]
                ]
            ],
            [
                'path' => '/menu',
                'component' => 'Layout',
                'redirect' => '/menu/list',
                'title' => '后台菜单',
                'icon' => 'el-icon-menu',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'menu/list',
                        'name' => 'menuList',
                        'title' => '菜单列表'
                    ]
                ]
            ],
            [
                'path' => '/aftersale',
                'component' => 'Layout',
                'redirect' => '/aftersale/list',
                'title' => '售后管理',
                'icon' => 'el-icon-menu',
                'always-show' => 1,
                'children' => [
                    [
                        'path' => 'list',
                        'component' => 'aftersale/list',
                        'name' => 'aftersaleList',
                        'title' => '售后列表'
                    ]
                ]
            ]
        ];

        foreach ($menus as $one) {
            $children = $one['children'];
            unset($one['children']);
            $one_menu = Menu::create($one);
            $one_menu->children()->createMany($children);
        }

    }
}
