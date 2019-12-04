<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

class UserChargesApiTest extends PluginTestCase
{
    public function test_fetching_charges()
    {
        $user = $this->createAuthenticatedUser();
        $card = StripeManager::createCard($user, 'tok_amex');

        for ($i = 0; $i < 3; $i++) {
            StripeManager::createCharge([
                'amount' => 1000,
                'currency' => 'usd',
                'customer' => $user->bedard_saas_customer_id,
                'description' => 'Charge #'.$i,
                'source' => $card->id,
            ]);
        }

        $response1 = $this->get('/api/bedard/saas/user/charges?limit=2');
        $response1->assertStatus(200);

        $data1 = json_decode($response1->getContent(), true);
        $this->assertEquals(2, count($data1['data']));

        $response2 = $this->get('/api/bedard/saas/user/charges?after='.$data1['data'][1]['id'].'&limit=2');
        $response2->assertStatus(200);

        $data2 = json_decode($response2->getContent(), true);
        $this->assertEquals(1, count($data2['data']));
        $this->assertFalse($data2['has_more']);
    }
}
