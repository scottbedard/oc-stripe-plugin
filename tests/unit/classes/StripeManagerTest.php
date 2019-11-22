<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

class StripeManagerTest extends PluginTestCase
{
    public function test_listing_products()
    {
        $products = StripeManager::listProducts();

        $this->assertEquals('/v1/products', $products->url);
        $this->assertEquals('list', $products->object);
    }
}
