<?php

$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware' => [
        'api.auth',
        // 减少transform的包裹层
        'serializer:array',
        'bindings'
    ],
    'prefix' => 'admin'
];

$api->version('v1', function ($api) use($params) {
    $api->group($params, function ($api) {
        /**
        用户管理
         */
        // 禁用用户
        $api->patch('users/{user}/lock', [\App\Http\Controllers\Admin\UserController::class, 'lock']);
        // 用户管理资源路由
        $api->resource('users', \App\Http\Controllers\Admin\UserController::class, [
            'only' => ['index', 'show']
        ]);
        /**
        分类管理
         */
        // 分类管理资源路由
        $api->patch('category/{category}/status', [\App\Http\Controllers\Admin\CategoryController::class, 'status']);
        $api->resource('category', \App\Http\Controllers\Admin\CategoryController::class, [
            'except' => ['destroy']
        ]);

        /**
        商品管理
         */
        // 商品状态
        $api->patch('goods/{good}/status', [\App\Http\Controllers\Admin\GoodsController::class, 'is_on']);
        // 商品是否推荐
        $api->patch('goods/{good}/recommend', [\App\Http\Controllers\Admin\GoodsController::class, 'is_recommend']);
        $api->resource('goods', \App\Http\Controllers\Admin\GoodsController::class, [
            'except' => ['destroy']
        ]);

        /**
        评论管理
         */
        $api->get('comments', [\App\Http\Controllers\Admin\CommentController::class, 'index']);
        $api->get('comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'show']);
        $api->patch('comments/{comment}/reply', [\App\Http\Controllers\Admin\CommentController::class, 'reply']);

        /**
        订单管理
         */
        $api->get('orders', [\App\Http\Controllers\Admin\OrdersController::class, 'index']);
        $api->get('orders/{orders}', [\App\Http\Controllers\Admin\OrdersController::class, 'show']);
        $api->patch('orders/{orders}/post', [\App\Http\Controllers\Admin\OrdersController::class, 'post']);
    });
});
