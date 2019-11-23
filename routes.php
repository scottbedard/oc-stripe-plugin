<?php

if (config('bedard.saas::apiEnable')) {
    Route::prefix(config('bedard.saas::apiPrefix'))->group(function () {
        Route::get('plans', 'Bedard\Saas\Http\Controllers\PlansController@index');
        Route::get('products', 'Bedard\Saas\Http\Controllers\ProductsController@index');
    });
}
