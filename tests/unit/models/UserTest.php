<?php

namespace Bedard\Stripe\Tests\Unit\Models;

use Bedard\Stripe\Tests\PluginTestCase;
use Faker\Factory;
use Log;
use StripeManager;

class UserTest extends PluginTestCase
{
    public function test_creating_a_user_creates_a_stripe_customer()
    {
        $user = $this->createUser();

        $this->assertStringStartsWith('cus_', $user->bedard_stripe_customer_id);

        $customer = StripeManager::retrieveCustomer($user);

        $this->assertEquals($user->email, $customer->email);
        $this->assertEquals($user->name.' '.$user->surname, $customer->name);
    }

    public function test_updating_a_user_email_updates_the_stripe_customer()
    {
        $faker = Factory::create();

        $user = $this->createUser();
        $user->email = $faker->email;
        $user->save();

        $customer = StripeManager::retrieveCustomer($user);

        $this->assertEquals($user->email, $customer->email);
    }

    public function test_deleting_a_user_deletes_the_stripe_customer()
    {
        $user = $this->createUser();
        $user->delete();

        $customer = StripeManager::retrieveCustomer($user);

        $this->assertTrue($customer->deleted);
    }

    public function test_an_error_is_logged_if_the_customer_deletion_fails()
    {
        // create a user / customer pair
        $user = $this->createUser();

        // set the customer id to something invalid so when we delete the
        // user stripe can't find the related customer and throws an error
        $user->bedard_stripe_customer_id = 'cus_invalid';
        $user->save();

        // deleting the customer should log an error
        Log::shouldReceive('error')->once();

        $user->delete();
    }
}
