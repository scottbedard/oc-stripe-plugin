<?php

if (config('bedard.saas::apiEnable')) {
    Route::prefix(config('bedard.saas::apiPrefix'))->group(function () {
        //
        // guest routes
        //

        // products
        Route::get('products', 'Bedard\Saas\Http\Controllers\ProductsController@index');

        //
        // authenticated routes
        //
        Route::group(['middleware' => 'RainLab\User\Classes\AuthMiddleware'], function () {

            // cards
            Route::post('cards', 'Bedard\Saas\Http\Controllers\CardsController@create');
        });
    });
}
