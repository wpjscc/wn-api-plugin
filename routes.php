<?php


Route::group([
    'middleware' => ['api'],
    'prefix' => Config::get('api.prefix', 'api')

], function () {

    Event::fire('api.beforeRoute');

    if (config('sanctum.support_default_route')) {
        Route::group([
            'prefix' => 'sanctum'
        ], function () {
            Route::post('/token', 'Wpjscc\Api\Http\Controllers\AuthController@login');
            Route::middleware('auth:sanctum')->get('user', 'Wpjscc\Api\Http\Controllers\AuthController@user');
        });
    }
    


    Route::any('{slug?}', 'Wpjscc\Api\Classes\ApiController@run')->where('slug', '(.*)?');

});







