<?php

namespace Bedard\Saas\Tests\Unit\Models;

use Bedard\Saas\Tests\PluginTestCase;
use Bedard\Saas\Tests\UserFactory;
use RainLab\User\Models\User;
use StripeIntegration;

class UserTest extends PluginTestCase
{
    public function test_creating_a_user_creates_a_stripe_customer()
    {
        $user = User::find(factory(User::class)->create()->id);
        
        $this->assertStringStartsWith('cus_', $user->bedard_saas_customer_id);

        $customer = StripeIntegration::retrieveCustomer($user);

        $this->assertEquals($user->email, $customer->email);
    }
}
