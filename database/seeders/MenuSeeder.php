<?php

namespace Database\Seeders;

use App\Models\Category;
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
        //

        $menus = [
            [
                'name' => '用户管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '商城用户',
                        'group' => 'menu',
                        'level' => 2
                    ],
                    [
                        'name' => '后台用户',
                        'group' => 'menu',
                        'level' => 2
                    ]
                ]
            ],
            [
                'name' => '订单管理',
                'group' => 'menu',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '订单列表',
                        'group' => 'menu',
                        'level' => 2
                    ]
                ]
            ]
        ];

        foreach ($menus as $one) {
            $children = $one['children'];
            unset($one['children']);
            $one_menu = Category::create($one);
            $one_menu->children()->createMany($children);
        }

        forget_cache_category();
    }
}
