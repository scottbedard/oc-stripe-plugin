<?php

namespace Bedard\Stripe\Http\Controllers;

use Bedard\Stripe\Classes\ApiController;
use StripeManager;

class PlansController extends ApiController
{
    /**
     * List active plans.
     */
    public function index()
    {
        $params = array_merge(input(), [
            'active' => true,
        ]);

        $products = StripeManager::listPlans($params);

        return [
            'data'     => $products->data,
            'has_more' => $products->has_more,
        ];
    }
}
