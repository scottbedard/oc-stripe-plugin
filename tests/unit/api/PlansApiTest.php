<?php

namespace Bedard\Stripe\Tests\Unit\Classes;

use Bedard\Stripe\Tests\PluginTestCase;
use Bedard\Stripe\Tests\Stubs\PlanStub;
use Mockery;

class PlansApiTest extends PluginTestCase
{
    public function test_listing_plans()
    {
        $plansFixture = self::jsonFixture('plans.json');

        Mockery::namedMock('Stripe\Plan', PlanStub::class)
            ->shouldReceive('all')
            ->once()
            ->andReturn($plansFixture);

        $response = $this->get('/api/bedard/stripe/plans');
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['has_more']);
        $this->assertEquals($data['data'][0]['id'], $plansFixture->data[0]->id);
    }
}
