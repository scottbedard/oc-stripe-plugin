<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable API
    |--------------------------------------------------------------------------
    |
    | The HTTP API exists to send requests between your application and Stripe.
    | Set this value to false to disable all API routes.
    |
    */

    'apiEnable' => env('BEDARD_STRIPE_API_ENABLE', true),

    /*
    |--------------------------------------------------------------------------
    | API prefix
    |--------------------------------------------------------------------------
    |
    | By default, all HTTP routes will be grouped behind the following url. If
    | this causes a conflict, set this value to overridesthe route group.
    |
    */

    'apiPrefix' => env('BEDARD_STRIPE_API_PREFIX', '/api/bedard/stripe'),
];
