<?php

namespace Bedard\Stripe\Tests\Unit\Classes;

use Bedard\Stripe\Tests\PluginTestCase;
use StripeManager;

class UserCustomerApiTest extends PluginTestCase
{
    public function test_updating_a_users_default_payment_source()
    {
        $user = $this->createAuthenticatedUser();
        $one = StripeManager::createCard($user, 'tok_amex');
        $two = StripeManager::createCard($user, 'tok_amex');

        $customer = StripeManager::retrieveCustomer($user);
        $this->assertEquals($one->id, $customer->default_source);

        $response = $this->post('/api/bedard/stripe/user/customer/default-source', [
            'source' => $two->id,
        ]);

        $response->assertStatus(200);

        $customer = StripeManager::retrieveCustomer($user);
        $this->assertEquals($two->id, $customer->default_source);
    }
}
