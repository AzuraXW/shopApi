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
        // 分类管理资源路由
        $api->resource('category', \App\Http\Controllers\Admin\CategoryController::class, [
            'except' => ['destroy']
        ]);
    });
});
