<?php

if (config('bedard.saas::apiEnable')) {
    Route::prefix(config('bedard.saas::apiPrefix'))
        ->middleware('web')
        ->group(function () {
            //
            // non-authenticated routes
            //

            // plans
            Route::get('plans', 'Bedard\Saas\Http\Controllers\PlansController@index');

            // products
            Route::get('products', 'Bedard\Saas\Http\Controllers\ProductsController@index');

            //
            // authenticated routes
            //
            Route::prefix('user')
                ->middleware('RainLab\User\Classes\AuthMiddleware')
                ->group(function () {
                    // cards
                    Route::delete('cards/{card}', 'Bedard\Saas\Http\Controllers\UserCardsController@destroy');
                    Route::get('cards', 'Bedard\Saas\Http\Controllers\UserCardsController@index');
                    Route::post('cards', 'Bedard\Saas\Http\Controllers\UserCardsController@create');

                    // charges
                    Route::get('charges', 'Bedard\Saas\Http\Controllers\UserChargesController@index');

                    // customer
                    Route::post('customer/default-source', 'Bedard\Saas\Http\Controllers\UserCustomerController@defaultSource');

                    // subscriptions
                    Route::delete('subscriptions/{subscription}', 'Bedard\Saas\Http\Controllers\UserSubscriptionsController@destroy');
                    Route::get('subscriptions', 'Bedard\Saas\Http\Controllers\UserSubscriptionsController@index');
                    Route::match(['patch', 'put'], 'subscriptions/{subscription}', 'Bedard\Saas\Http\Controllers\UserSubscriptionsController@update');
                    Route::post('subscriptions', 'Bedard\Saas\Http\Controllers\UserSubscriptionsController@create');
                });
        });
}
