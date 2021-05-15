<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // 首页数据
    $api->get('index', [\App\Http\Controllers\Api\IndexController::class, 'index']);
    // 用户登录
    $api->post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    // 商品详情
    $api->get('goods/{id}', [\App\Http\Controllers\Api\GoodsController::class, 'show']);
    $api->get('goods', [\App\Http\Controllers\Api\GoodsController::class, 'list']);
    // aliyun支付通知
    $api->any('pay/notify/aliyun', [\App\Http\Controllers\Api\PayController::class, 'notifyAliyun']);

    // 需要权限的api
    $api->group(['middleware' => ['api.auth', 'bindings', 'serializer:array']], function ($api) {
        $api->get('user/info', [\App\Http\Controllers\Api\UserController::class, 'show']);
        $api->put('user/password', [\App\Http\Controllers\Api\UserController::class, 'updatePwd']);

        // 更改用户头像
        $api->post('user/avatar', [\App\Http\Controllers\Api\UserController::class, 'updateAvatar']);

        // 用户购物车
        $api->resource('cart', \App\Http\Controllers\Api\CartController::class, [
            'except' => ['show']
        ]);

        /**
         * 订单相关api
         */
        // 订单预览
        $api->get('orders/preview', [\App\Http\Controllers\Api\OrderController::class, 'preview']);
        $api->post('orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
        $api->get('orders/{orders}', [\App\Http\Controllers\Api\OrderController::class, 'show']);

        $api->post('orders/{orders}/pay', [\App\Http\Controllers\Api\PayController::class, 'pay']);
    });
});
