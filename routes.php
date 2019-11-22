<?php

Route::prefix(config('bedard.saas::apiPrefix'))
    ->middleware('web', 'Bedard\Saas\Http\Middleware\ApiMiddleware')
    ->group(function () {
        Route::get('products', 'Bedard\Saas\Http\Controllers\ProductsController@index');
    });
