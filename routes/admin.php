<?php

$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware' => [
        'jwt.role:admin',
//        'check.permission',
        // 减少transform的包裹层
        'serializer:array',
        'bindings'
    ]
];

$api->version('v1', function ($api) use($params) {
    $api->group(['prefix' => 'admin'], function ($api) use($params) {
        $api->post('login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
        $api->group(['middleware' => 'auth:admin'], function ($api) {
            $api->post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);
            $api->post('refresh', [App\Http\Controllers\Admin\AuthController::class, 'refresh']);
            $api->post('me', [App\Http\Controllers\Admin\AuthController::class, 'me']);
            // 阿里云OSS上传token
            $api->get('oss/token', [App\Http\Controllers\Auth\OssController::class, 'token']);
        });

        $api->group($params, function ($api) {
            /**
            用户管理
             */
            // 禁用用户
            $api->patch('users/{user}/lock', [\App\Http\Controllers\Admin\UserController::class, 'lock'])->name('users.lock');
            $api->delete('users/{user}/delete', [\App\Http\Controllers\Admin\UserController::class, 'delete'])->name('users.delete');
            // 用户管理资源路由
            $api->resource('users', \App\Http\Controllers\Admin\UserController::class, [
                'only' => ['index', 'show', 'store']
            ]);
            // 更新用户头像
            $api->post('users/{user}/avatar', [\App\Http\Controllers\Admin\AuthController::class, 'updateAvatar']);
            // 更新用户个人信息
            $api->post('users/{user}/profile', [\App\Http\Controllers\Admin\AuthController::class, 'updateProfile']);
            $api->post('users/{user}/update_pwd', [\App\Http\Controllers\Auth\PasswordResetController::class, 'AdminUpdatePwdByEmail']);
            $api->post('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'giveRole'])->name('users.giveRole');
            $api->post('users/{user}/role/update', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
            $api->delete('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'removeRole'])->name('users.removeRole');
            $api->post('users/{user}/permission', [\App\Http\Controllers\Admin\UserController::class, 'givePermission'])->name('users.givePermission');
            $api->delete('users/{user}/permission', [\App\Http\Controllers\Admin\UserController::class, 'revokePermission'])->name('users.revokePermission');

            /**
            分类管理
             */
            // 分类管理资源路由
            $api->patch('category/{category}/status', [\App\Http\Controllers\Admin\CategoryController::class, 'status'])->name('category.status');
            $api->resource('category', \App\Http\Controllers\Admin\CategoryController::class, [
                'except' => ['destroy']
            ]);
            // 为分类添加品牌
            $api->post('category/{category}/brand', [\App\Http\Controllers\Admin\CategoryController::class, 'addBrand']);
            // 删除分类下的品牌
            $api->post('category/{category}/delBrand', [\App\Http\Controllers\Admin\CategoryController::class, 'deleteBrand']);
            /**
            商品管理
             */
            // 商品状态
            $api->patch('goods/{good}/status', [\App\Http\Controllers\Admin\GoodsController::class, 'is_on'])->name('goods.status');
            // 商品是否推荐
            $api->patch('goods/{good}/recommend', [\App\Http\Controllers\Admin\GoodsController::class, 'is_recommend'])->name('goods.recommend');
            $api->resource('goods', \App\Http\Controllers\Admin\GoodsController::class, [
                'except' => ['destroy']
            ]);

            /**
            评论管理
             */
            $api->get('comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
            $api->get('comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'show'])->name('comments.show');
            $api->patch('comments/{comment}/reply', [\App\Http\Controllers\Admin\CommentController::class, 'reply'])->name('comments.reply');

            /**
            订单管理
             */
            $api->get('orders', [\App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('orders.index');
            $api->get('orders/{orders}', [\App\Http\Controllers\Admin\OrdersController::class, 'show'])->name('orders.show');
            $api->patch('orders/{orders}/post', [\App\Http\Controllers\Admin\OrdersController::class, 'post'])->name('orders.post');

            $api->resource('slides', \App\Http\Controllers\Admin\SlidesController::class, [
                'except' => ['destroy']
            ]);
            $api->patch('slides/{slide}/status', [\App\Http\Controllers\Admin\SlidesController::class, 'status'])->name('slides.status');

            // 后台菜单
            $api->get('menus', [\App\Http\Controllers\Admin\MenuController::class, 'index']);
            $api->get('menus/list', [\App\Http\Controllers\Admin\MenuController::class, 'list']);
            $api->post('menus/{menu}/cache', [\App\Http\Controllers\Admin\MenuController::class, 'componentCache']);
            $api->post('menus/{menu}/giveRole', [\App\Http\Controllers\Admin\MenuController::class, 'giveRole']);

            /**
            角色权限分配
             */
            // 返回所有的权限
            $api->get('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
            // 返回角色以及对应的权限
            $api->get('roles', [\App\Http\Controllers\Admin\RolesController::class, 'index'])->name('roles.index');
            // 返回所有的角色
            $api->get('roles/list', [\App\Http\Controllers\Admin\RolesController::class, 'list'])->name('roles.list');
            // 添加角色
            $api->post('roles', [\App\Http\Controllers\Admin\RolesController::class, 'store'])->name('roles.store');
            $api->post('roles/{role}/update', [\App\Http\Controllers\Admin\RolesController::class, 'update'])
                ->middleware('checkavailablerole')
                ->name('roles.update');
            $api->delete('roles/{role}/delete', [\App\Http\Controllers\Admin\RolesController::class, 'delete'])
                ->middleware('checkavailablerole')
                ->name('roles.delete');
            // 为角色添加权限
            $api->post('roles/{role}/permission', [\App\Http\Controllers\Admin\RolesController::class, 'addPermission'])
                ->middleware('checkavailablerole')
                ->name('roles.addPermission');
            $api->put('roles/{role}/permission', [\App\Http\Controllers\Admin\RolesController::class, 'updatePermission'])
                ->middleware('checkavailablerole')
                ->name('roles.updatePermission');

            /* 商品规格组和规格key */
            // 添加商品规格组
            $api->post('specGroup', [\App\Http\Controllers\Admin\SpecController::class, 'addSpecGroup']);
            // 删除商品规格组
            $api->delete('specGroup/{specGroup}', [\App\Http\Controllers\Admin\SpecController::class, 'deleteSpecGroup']);
            // 更新商品规格组
            $api->post('specGroup/{specGroup}/update', [\App\Http\Controllers\Admin\SpecController::class, 'updateSpecGorup']);
            // 添加规格组参数
            $api->post('specParam', [\App\Http\Controllers\Admin\SpecController::class, 'addSpecParams']);
            // 更新规格组参数
            $api->post('specParam/{specParam}/update', [\App\Http\Controllers\Admin\SpecController::class, 'updateSpecParam']);
            // 删除规格组参数
            $api->delete('specParam/{specParam}', [\App\Http\Controllers\Admin\SpecController::class, 'deleteSpecParam']);
            // 品牌列表
            $api->get('brand/list', [\App\Http\Controllers\Admin\BrandController::class, 'brandList']);
            // 添加品牌
            $api->post('brand', [\App\Http\Controllers\Admin\BrandController::class, 'addBrand']);
            // 删除品牌
            $api->delete('brand/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'deleteBrand']);
            // 更新品牌
            $api->post('brand/{brand}/update', [\App\Http\Controllers\Admin\BrandController::class, 'updateBrand']);

            // 商品集
            // 添加商品集
            $api->post('spu', [\App\Http\Controllers\Admin\SpuController::class, 'addSpu']);
            // 商品集列表
            $api->get('spu/list', [\App\Http\Controllers\Admin\SpuController::class, 'spuList']);

            // 商品SKU
            $api->post('sku', [\App\Http\Controllers\Admin\SkuController::class, 'addSku']);
            // 根据spu_id查询商品
            $api->get('sku', [\App\Http\Controllers\Admin\SkuController::class, 'querySku']);
        });
    });
});
