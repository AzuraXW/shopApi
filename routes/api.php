<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // 首页数据
    $api->get('index', [\App\Http\Controllers\Api\IndexController::class, 'index']);
    // 用户登录
    $api->post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);

    // 需要权限的api
    $api->group(['middleware' => ['api.auth', 'bindings']], function ($api) {
        $api->get('user/info', [\App\Http\Controllers\Api\UserController::class, 'show']);
        $api->put('user/password', [\App\Http\Controllers\Api\UserController::class, 'updatePwd']);
    });
});
