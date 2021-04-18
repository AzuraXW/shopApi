<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
   $api->group(['prefix' => 'auth'], function ($api) {
       $api->post('register', "App\Http\Controllers\Auth\RegisterController@store");
       $api->post('login', "App\Http\Controllers\Auth\LoginController@login");
       $api->group(['middleware' => 'api.auth'], function ($api) {
           $api->post('logout', "App\Http\Controllers\Auth\LoginController@logout");
           $api->post('refresh', "App\Http\Controllers\Auth\LoginController@refresh");
           // 阿里云OSS上传token
           $api->get('oss/token', [App\Http\Controllers\Auth\OssController::class, 'token']);
       });
       $api->post('me', "App\Http\Controllers\Auth\LoginController@me");
   });
});
