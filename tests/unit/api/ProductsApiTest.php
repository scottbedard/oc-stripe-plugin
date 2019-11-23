<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\Stubs\PlanStub;
use Bedard\Saas\Tests\Stubs\ProductStub;
use Mockery;

class ProductsApiTest extends PluginTestCase
{
    public function test_products_index()
    {
        $productsFixture = self::jsonFixture('products.json');
        $plansFixture = self::jsonFixture('plans.json');

        Mockery::namedMock('Stripe\Product', ProductStub::class)
            ->shouldReceive('all')
            ->andReturn($productsFixture);

        Mockery::namedMock('Stripe\Plan', PlanStub::class)
            ->shouldReceive('all')
            ->andReturn($plansFixture);

        $response = $this->get('/api/bedard/saas/products');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($productsFixture->data[0]->id, $data[0]['id']);
        $this->assertEquals($plansFixture->data[0]->id, $data[0]['plans'][0]['id']);
    }
}
