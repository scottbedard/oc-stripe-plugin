<?php

namespace Bedard\Stripe\Facades;

use October\Rain\Support\Facade;

class StripeManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bedard.stripe.stripe';
    }
}
