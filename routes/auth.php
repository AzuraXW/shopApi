<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
   $api->group(['prefix' => 'auth'], function ($api) {
       $api->post('register', "App\Http\Controllers\Auth\RegisterController@store");
   });
});
