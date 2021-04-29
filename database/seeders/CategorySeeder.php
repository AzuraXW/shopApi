<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充分类信息
        $cateogies = [
            [
                'name' => '电子数码',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '手机',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '小米',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '华为',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '魅族',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '苹果',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ],
                    [
                        'name' => '电脑',
                        'level' => 2,
                        'group' => 'goods',
                        'children' => [
                            [
                                'name' => '戴尔',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '苹果',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '神州',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '联想',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '荣耀',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '机械师',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => '服装服饰',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '男装',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '皮带',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '西装',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '皮鞋',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '领带',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ],
                    [
                        'name' => '女装',
                        'level' => 2,
                        'group' => 'goods',
                        'children' => [
                            [
                                'name' => '连衣裙',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '高跟鞋',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '神州',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '围脖',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => '个人洗护',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '沐浴露',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '舒肤佳',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '威露士',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '六神',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ],
                    [
                        'name' => '洗发露',
                        'level' => 2,
                        'group' => 'goods',
                        'children' => [
                            [
                                'name' => '飘柔',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '海飞丝',
                                'group' => 'goods',
                                'level' => 3
                            ],
                            [
                                'name' => '夏士莲',
                                'group' => 'goods',
                                'level' => 3
                            ]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($cateogies as $l1) {
            $l1_tmp = $l1;
            unset($l1_tmp['children']);
            $l1_modle = Category::create($l1_tmp);
            foreach ($l1['children'] as $l2) {
                $l2_tmp = $l2;
                unset($l2_tmp['children']);
                $l2_tmp['pid'] = $l1_modle->id;
                $l2_model = Category::create($l2_tmp);
                $l2_model->children()->createMany($l2['children']);
            }
        }
    }
}
