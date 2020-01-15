<?php

namespace Bedard\Stripe\Http\Controllers;

use Bedard\Stripe\Classes\ApiController;
use StripeManager;

class ProductsController extends ApiController
{
    /**
     * List active products.
     */
    public function index()
    {
        $params = array_merge(input(), [
            'active' => true,
        ]);

        array_forget($params, 'plans');

        $plans = array_key_exists('plans', input());
        $products = StripeManager::listProducts($params, $plans);

        return [
            'data'     => $products->data,
            'has_more' => $products->has_more,
        ];
    }
}
