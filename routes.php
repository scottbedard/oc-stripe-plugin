<?php

if (config('bedard.saas::apiEnable')) {
    Route::prefix(config('bedard.saas::apiPrefix'))
        ->middleware('web')
        ->group(function () {
        //
        // guest routes
        //

        // products
        Route::get('products', 'Bedard\Saas\Http\Controllers\ProductsController@index');

        //
        // authenticated routes
        //
        Route::middleware('RainLab\User\Classes\AuthMiddleware')->group(function () {
            
            // cards
            Route::get('cards', 'Bedard\Saas\Http\Controllers\CardsController@index');
            Route::post('cards', 'Bedard\Saas\Http\Controllers\CardsController@create');
        });
    });
}
