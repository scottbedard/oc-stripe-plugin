<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
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
