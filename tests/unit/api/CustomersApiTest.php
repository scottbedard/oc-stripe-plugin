<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

class CustomersApiTest extends PluginTestCase
{
    public function test_patch_updating_a_user()
    {
        $user = $this->createAuthenticatedUser();

        $request = $this->patch('/api/bedard/saas/customers', [
            'description' => 'An awesome customer',
        ]);

        $request->assertStatus(200);

        $data = json_decode($request->getContent(), true);
        $customer = StripeManager::retrieveCustomer($user);
        
        $this->assertEquals($customer->description, 'An awesome customer');
        $this->assertEquals($customer->id, $data['id']);
    }

    public function test_put_updating_a_user()
    {
        $user = $this->createAuthenticatedUser();

        $request = $this->put('/api/bedard/saas/customers', [
            'description' => 'An awesome customer',
        ]);

        $request->assertStatus(200);

        $data = json_decode($request->getContent(), true);
        $customer = StripeManager::retrieveCustomer($user);
        
        $this->assertEquals($customer->description, 'An awesome customer');
        $this->assertEquals($customer->id, $data['id']);
    }
}