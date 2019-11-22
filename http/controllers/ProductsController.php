<?php

namespace Bedard\Saas\Http\Controllers;

use Bedard\Saas\Classes\ApiController;
use StripeManager;

class ProductsController extends ApiController
{
    /**
     * List products.
     */
    public function index()
    {
        return StripeManager::listProducts();
    }
}
