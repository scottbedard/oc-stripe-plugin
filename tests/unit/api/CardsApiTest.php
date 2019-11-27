<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

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

    public function test_listing_customer_cards()
    {
        $user = $this->createAuthenticatedUser();

        StripeManager::createCard($user, 'tok_amex');
        StripeManager::createCard($user, 'tok_amex');
        StripeManager::createCard($user, 'tok_amex');

        $response = $this->get('/api/bedard/saas/cards?limit=2');

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['has_more']);
        $this->assertEquals(2, count($data['data']));
    }
}