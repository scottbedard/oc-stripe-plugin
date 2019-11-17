<?php

namespace Bedard\Saas\Facades;

use October\Rain\Support\Facade;

class StripeManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bedard.saas.stripe';
    }
}
