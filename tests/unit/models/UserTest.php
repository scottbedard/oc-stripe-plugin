<?php

namespace Bedard\Saas\Tests\Unit\Models;

use Bedard\Saas\Tests\PluginTestCase;
use Faker\Factory;
use StripeIntegration;

class UserTest extends PluginTestCase
{
    public function test_creating_a_user_creates_a_stripe_customer()
    {
        $user = $this->createUser();

        $this->assertStringStartsWith('cus_', $user->bedard_saas_customer_id);

        $customer = StripeIntegration::retrieveCustomer($user);

        $this->assertEquals($user->email, $customer->email);
    }

    public function test_updating_a_user_updates_the_stripe_customer()
    {
        $faker = Factory::create();

        $user = $this->createUser();
        $user->email = $faker->email;
        $user->save();

        $customer = StripeIntegration::retrieveCustomer($user);

        $this->assertEquals($user->email, $customer->email);
    }

    public function test_deleting_a_user_deletes_the_stripe_customer()
    {
        $user = $this->createUser();
        $user->delete();

        $customer = StripeIntegration::retrieveCustomer($user);

        $this->assertTrue($customer->deleted);
    }
}
