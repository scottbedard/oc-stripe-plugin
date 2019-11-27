<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Auth;
use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\Stubs\PlanStub;
use Bedard\Saas\Tests\Stubs\ProductStub;
use Mockery;

class CardsApiTest extends PluginTestCase
{
    public function test_creating_a_card()
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->post('/api/bedard/saas/cards', [
            'token' => 'tok_amex',
        ]);
        
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals('card', $data['object']);
    }
}