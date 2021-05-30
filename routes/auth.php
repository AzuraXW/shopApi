<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
   $api->group(['prefix' => 'auth'], function ($api) {
       // 邮箱相关api
       $api->post('email/code', [\App\Http\Controllers\Auth\BindController::class, 'emailCode']);
       $api->post('email/update', [\App\Http\Controllers\Auth\BindController::class, 'updateEmail']);

       // 绑定手机号
       $api->post('phone/code', [\App\Http\Controllers\Auth\BindController::class, 'phoneCode']);
       $api->post('phone/update', [\App\Http\Controllers\Auth\BindController::class, 'phone']);

       // 通过邮箱找回密码
       $api->post('password/reset/email/code', [\App\Http\Controllers\Auth\PasswordResetController::class, 'emailCode']);
       $api->post('password/reset/email', [\App\Http\Controllers\Auth\PasswordResetController::class, 'updatePwdByEmail']);
   });
});
