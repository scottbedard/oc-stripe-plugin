<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\Stubs\ProductStub;
use Mockery;

class ProductsApiTest extends PluginTestCase
{
    public function test_listing_products()
    {
        $productsFixture = self::jsonFixture('products.json');

        Mockery::namedMock('Stripe\Product', ProductStub::class)
            ->shouldReceive('all')
            ->once()
            ->andReturn($productsFixture);

        $response = $this->get('/api/bedard/saas/products');
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['has_more']);
        $this->assertEquals($data['data'][0]['id'], $productsFixture->data[0]->id);
        $this->assertArrayNotHasKey('plans', $data['data'][0]);
    }
}
