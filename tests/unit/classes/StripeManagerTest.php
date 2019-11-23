<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\Stubs\PlanStub;
use Bedard\Saas\Tests\Stubs\ProductStub;
use Mockery;
use StripeManager;

class StripeManagerTest extends PluginTestCase
{
    public function test_getting_products_with_plans()
    {
        $productsFixture = self::jsonFixture('products.json');
        $plansFixture = self::jsonFixture('plans.json');

        Mockery::namedMock('Stripe\Product', ProductStub::class)
            ->shouldReceive('all')
            ->times(1)
            ->withArgs([
                ['active' => true],
            ])
            ->andReturn($productsFixture);

        Mockery::namedMock('Stripe\Plan', PlanStub::class)
            ->shouldReceive('all')
            ->times(1)
            ->withArgs([
                [
                    'active'  => true,
                    'product' => $productsFixture->data[0]->id,
                ],
            ])
            ->andReturn($plansFixture);

        StripeManager::getProductsWithPlans();
    }
}
