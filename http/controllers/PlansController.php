<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
use StripeManager;

class PlansController extends ApiController
{
    /**
     * List plans.
     */
    public function index()
    {
        return StripeManager::getPlans([
            'active' => true,
            'expand' => ['data.product'],
        ]);
    }
}
