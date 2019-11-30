<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
use StripeManager;

class ProductsController extends ApiController
{
    /**
     * List active products, optionally with active child plans.
     */
    public function index()
    {
        $params = array_merge(input(), [
            'active' => true,
        ]);

        $products = StripeManager::listProducts($params);

        return [
            'data'     => $products->data,
            'has_more' => $products->has_more,
        ];
    }
}
