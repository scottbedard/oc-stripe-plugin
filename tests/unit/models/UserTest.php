<?php

namespace Bedard\Saas\Tests\Unit\Models;

use Bedard\Saas\Tests\PluginTestCase;
use Faker\Factory;
use StripeManager;

class UserTest extends PluginTestCase
{
    public function test_creating_a_user_creates_a_stripe_customer()
    {
        $user = $this->createUser();

        $this->assertStringStartsWith('cus_', $user->bedard_saas_customer_id);

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
        // $user->delete();

        $customer = StripeManager::retrieveCustomer($user);

        print_r($user->toArray());

        dd($customer);

        // $this->assertTrue($customer->deleted);
    }
}
