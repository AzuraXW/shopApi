<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // 首页数据
    $api->get('index', [\App\Http\Controllers\Api\IndexController::class, 'index']);
    // 需要权限的api
    $api->group(['middleware' => 'api.auth'], function ($api) {

    });
});
