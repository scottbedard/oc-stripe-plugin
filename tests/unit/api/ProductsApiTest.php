<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\Stubs\ProductStub;
use Mockery;
use StripeManager;

class ProductsApiTest extends PluginTestCase
{
    public function test_listing_products()
    {
        $product = StripeManager::createProduct([
            'active' => true,
            'name' => 'Basic',
        ]);

        $response = $this->get('/api/bedard/saas/products');
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals($product->id, $data['data'][0]['id']);
        $this->assertArrayHasKey('has_more', $data);
        $this->assertArrayNotHasKey('plans', $data['data'][0]);
    }

    public function test_listing_products_with_active_plans()
    {
        $product = StripeManager::createProduct([
            'active' => true,
            'name' => 'Basic',
        ]);
        
        $plan1 = StripeManager::createPlan([
            'active' => true,
            'amount' => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product' => $product->id,
        ]);
        
        $plan2 = StripeManager::createPlan([
            'active' => false,
            'amount' => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product' => $product->id,
        ]);

        $response = $this->get('/api/bedard/saas/products?plans');
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(1, count($data['data'][0]['plans']['data']));
        $this->assertEquals($plan1->id, $data['data'][0]['plans']['data'][0]['id']);
        $this->assertFalse($data['data'][0]['plans']['has_more']);
    }
}
