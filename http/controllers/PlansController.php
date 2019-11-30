<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
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
