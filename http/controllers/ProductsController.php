<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
use StripeManager;

class ProductsController extends ApiController
{
    /**
     * List active products with associated plans.
     */
    public function index()
    {
        return StripeManager::getProductsWithPlans();
    }
}
