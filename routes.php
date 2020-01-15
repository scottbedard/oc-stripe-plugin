<?php

if (config('bedard.stripe::apiEnable')) {
    Route::prefix(config('bedard.stripe::apiPrefix'))
        ->middleware('web')
        ->group(function () {
            //
            // non-authenticated routes
            //

            // plans
            Route::get('plans', 'Bedard\Stripe\Http\Controllers\PlansController@index');

            // products
            Route::get('products', 'Bedard\Stripe\Http\Controllers\ProductsController@index');

            //
            // authenticated routes
            //
            Route::prefix('user')
                ->middleware('RainLab\User\Classes\AuthMiddleware')
                ->group(function () {
                    // cards
                    Route::delete('cards/{card}', 'Bedard\Stripe\Http\Controllers\UserCardsController@destroy');
                    Route::get('cards', 'Bedard\Stripe\Http\Controllers\UserCardsController@index');
                    Route::post('cards', 'Bedard\Stripe\Http\Controllers\UserCardsController@create');

                    // charges
                    Route::get('charges', 'Bedard\Stripe\Http\Controllers\UserChargesController@index');

                    // customer
                    Route::post('customer/default-source', 'Bedard\Stripe\Http\Controllers\UserCustomerController@defaultSource');

                    // subscriptions
                    Route::delete('subscriptions/{subscription}', 'Bedard\Stripe\Http\Controllers\UserSubscriptionsController@destroy');
                    Route::get('subscriptions', 'Bedard\Stripe\Http\Controllers\UserSubscriptionsController@index');
                    Route::match(['patch', 'put'], 'subscriptions/{subscription}', 'Bedard\Stripe\Http\Controllers\UserSubscriptionsController@update');
                    Route::post('subscriptions', 'Bedard\Stripe\Http\Controllers\UserSubscriptionsController@create');
                });
        });
}
