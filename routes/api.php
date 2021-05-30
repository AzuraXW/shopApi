<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->post('login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    // 首页数据
    $api->get('index', [\App\Http\Controllers\Api\IndexController::class, 'index']);
    // 商品详情
    $api->get('goods/{id}', [\App\Http\Controllers\Api\GoodsController::class, 'show']);
    $api->get('goods', [\App\Http\Controllers\Api\GoodsController::class, 'list']);
    // aliyun支付通知
    $api->any('pay/notify/aliyun', [\App\Http\Controllers\Api\PayController::class, 'notifyAliyun']);

    // 需要权限的api
    $api->group(['middleware' => ['jwt.role:user', 'bindings', 'serializer:array']], function ($api) {
        $api->post('logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
        $api->get('me', [\App\Http\Controllers\Api\UserController::class, 'me']);
        $api->get('refresh', [\App\Http\Controllers\Api\UserController::class, 'refresh']);
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
        // 订单列表
        $api->get('orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
        // 订单预览
        $api->get('orders/preview', [\App\Http\Controllers\Api\OrderController::class, 'preview']);
        // 提交订单
        $api->post('orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
        // 订单详情
        $api->get('orders/{orders}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
        // 订单支付
        $api->post('orders/{orders}/pay', [\App\Http\Controllers\Api\PayController::class, 'pay']);
        // 快递查询
        $api->get('orders/{orders}/express', [\App\Http\Controllers\Api\OrderController::class, 'express']);
        // 收货
        $api->patch('orders/{orders}/confirm', [\App\Http\Controllers\Api\OrderController::class, 'confirm']);
        // 商品评论
        $api->post('orders/{orders}/comment', [\App\Http\Controllers\Api\CommentController::class, 'store']);

        // 省市区
        $api->get('region', [\App\Http\Controllers\Api\RegionController::class, 'show']);

        /**
         * 收货地址Api
         */
        $api->resource('address', \App\Http\Controllers\Api\AddressController::class);
        $api->patch('address/{address}/default', [\App\Http\Controllers\Api\AddressController::class, 'default']);
    });
});
