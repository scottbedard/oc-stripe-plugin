<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Tests\PluginTestCase;
use StripeManager;

class UserSubscriptionsApiTest extends PluginTestCase
{
    public function test_fetching_a_users_subscriptions()
    {
        // create a user and subscribe them to a plan
        $user = $this->createAuthenticatedUser();
        $product = StripeManager::createProduct(['name' => 'Basic']);
        $plan = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 0,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);

        StripeManager::subscribeUserToPlan($user, $plan->id);

        $response = $this->get('/api/bedard/saas/user/subscriptions');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($plan->id, $data['data'][0]['plan']['id']);
        $this->assertEquals(1, count($data['data']));
        $this->assertFalse($data['has_more']);
    }

    public function test_creating_a_subscription()
    {
        $user = $this->createAuthenticatedUser();

        StripeManager::createCard($user, 'tok_amex');

        $product = StripeManager::createProduct(['name' => 'Basic']);

        $plan = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);

        $response = $this->post('/api/bedard/saas/user/subscriptions', [
            'plan' => $plan->id,
        ]);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals('subscription', $data['data']['object']);
    }

    public function test_changing_a_subscription_plan_patch()
    {
        $user = $this->createAuthenticatedUser();

        StripeManager::createCard($user, 'tok_amex');

        $product = StripeManager::createProduct(['name' => 'Basic']);

        $monthly = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);

        $annual = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 10000,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);

        $subscription = StripeManager::subscribeUserToPlan($user, $monthly->id);

        $response = $this->patch('/api/bedard/saas/user/subscriptions/'.$subscription->id, [
            'plan' => $annual->id,
        ]);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(1, $data['data']['items']['total_count']);
        $this->assertEquals($annual->id, $data['data']['plan']['id']);
    }

    public function test_cancelling_a_subscription()
    {
        $user = $this->createAuthenticatedUser();
        $card = StripeManager::createCard($user, 'tok_amex');
        $product = StripeManager::createProduct(['name' => 'Basic']);
        $plan = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);
        $subscription = StripeManager::subscribeUserToPlan($user, $plan->id);

        $response = $this->delete('/api/bedard/saas/user/subscriptions/'.$subscription->id);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), 200);
        $this->assertTrue($data['data']['cancel_at_period_end']);
    }

    public function test_unauthorized_cancellation_of_a_subscription()
    {
        // user 1 has a subscription
        $user1 = $this->createActivatedUser();
        $card = StripeManager::createCard($user1, 'tok_amex');
        $product = StripeManager::createProduct(['name' => 'Basic']);
        $plan = StripeManager::createPlan([
            'active'   => true,
            'amount'   => 1000,
            'currency' => 'usd',
            'interval' => 'month',
            'product'  => $product->id,
        ]);
        $subscription = StripeManager::subscribeUserToPlan($user1, $plan->id);

        // user 2 does not, but is logged in
        $user2 = $this->createAuthenticatedUser();

        // cancelling the subscription from user 2's perspective should fail
        $response = $this->delete('/api/bedard/saas/user/subscriptions/'.$subscription->id);
        $response->assertStatus(401);
    }
}
