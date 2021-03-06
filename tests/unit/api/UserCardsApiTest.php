<?php

namespace Bedard\Stripe\Tests\Unit\Classes;

use Bedard\Stripe\Tests\PluginTestCase;
use StripeManager;

class UserCardsApiTest extends PluginTestCase
{
    public function test_creating_a_card()
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->post('/api/bedard/stripe/user/cards', ['token' => 'tok_amex']);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertStringStartsWith('card_', $data['data']['id']);
    }

    public function test_deleting_a_card()
    {
        $user = $this->createAuthenticatedUser();
        $one = StripeManager::createCard($user, 'tok_amex');
        $two = StripeManager::createCard($user, 'tok_amex');

        $this->assertEquals(2, count(StripeManager::listCustomerSources($user)->data));

        $response = $this->delete('/api/bedard/stripe/user/cards/'.$one->id);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($one->id, $data['id']);
        $this->assertTrue($data['deleted']);

        $cards = StripeManager::listCustomerSources($user)->data;
        $this->assertEquals($two->id, $cards[0]->id);
        $this->assertEquals(1, count($cards));
    }

    public function test_listing_customer_cards()
    {
        $user = $this->createAuthenticatedUser();
        $one = StripeManager::createCard($user, 'tok_amex');
        StripeManager::createCard($user, 'tok_amex');
        StripeManager::createCard($user, 'tok_amex');

        $response = $this->get('/api/bedard/stripe/user/cards?limit=2');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['has_more']);
        $this->assertEquals(2, count($data['data']));
        $this->assertEquals($one->id, $data['default_source']);
    }
}
