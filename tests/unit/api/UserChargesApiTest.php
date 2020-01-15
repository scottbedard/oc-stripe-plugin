<?php

namespace Bedard\Stripe\Tests\Unit\Classes;

use Bedard\Stripe\Tests\PluginTestCase;
use StripeManager;

class UserChargesApiTest extends PluginTestCase
{
    public function test_fetching_charges()
    {
        $user = $this->createAuthenticatedUser();
        $card = StripeManager::createCard($user, 'tok_amex');
        $charges = [];

        for ($i = 0; $i < 3; $i++) {
            $charge = StripeManager::createCharge([
                'amount'      => 1000,
                'currency'    => 'usd',
                'customer'    => $user->bedard_stripe_customer_id,
                'description' => 'Charge #'.$i,
                'source'      => $card->id,
            ]);

            $charges[] = $charge;
        }

        // first page
        $response1 = $this->get('/api/bedard/stripe/user/charges?limit=1');
        $response1->assertStatus(200);
        $data1 = json_decode($response1->getContent(), true);
        $this->assertEquals($charges[2]->id, $data1['data'][0]['id']);
        $this->assertFalse($data1['has_prev']);
        $this->assertTrue($data1['has_next']);

        // middle page (after)
        $response2 = $this->get('/api/bedard/stripe/user/charges?limit=1&after='.$charges[2]->id);
        $response2->assertStatus(200);
        $data2 = json_decode($response2->getContent(), true);
        $this->assertEquals($charges[1]->id, $data2['data'][0]['id']);
        $this->assertTrue($data2['has_prev']);
        $this->assertTrue($data2['has_next']);

        // middle page (before)
        $response3 = $this->get('/api/bedard/stripe/user/charges?limit=1&before='.$charges[0]->id);
        $response3->assertStatus(200);
        $data3 = json_decode($response3->getContent(), true);
        $this->assertEquals($charges[1]->id, $data3['data'][0]['id']);
        $this->assertTrue($data3['has_prev']);
        $this->assertTrue($data3['has_next']);

        // last page
        $response4 = $this->get('/api/bedard/stripe/user/charges?limit=1&after='.$charges[1]->id);
        $response4->assertStatus(200);
        $data4 = json_decode($response4->getContent(), true);
        $this->assertEquals($charges[0]->id, $data4['data'][0]['id']);
        $this->assertTrue($data4['has_prev']);
        $this->assertFalse($data4['has_next']);
    }
}
