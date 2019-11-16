<?php

namespace Bedard\Saas\Facades;

use October\Rain\Support\Facade;

class StripeIntegration extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bedard.saas.stripe';
    }
}