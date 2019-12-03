<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use October\Rain\Auth\AuthException;
use StripeManager;

class UserSubscriptionsController extends ApiController
{
    /**
     * Create a subscription.
     */
    public function create()
    {
        $user = Auth::getUser();

        $plan = post('plan');

        $subscription = StripeManager::subscribeUserToPlan($user, $plan);

        return [
            'data' => $subscription,
        ];
    }

    /**
     * Cancel a subscription.
     *
     * @param string $subscription
     */
    public function destroy($subscription)
    {
        $user = Auth::getUser();

        try {
            $subscription = StripeManager::cancelUserSubscription($user, $subscription);
        } catch (AuthException $e) {
            return response('Unauthorized', 401);
        }

        return [
            'data' => $subscription,
        ];
    }

    /**
     * List the authenticated users subscriptions.
     */
    public function index()
    {
        $user = Auth::getUser();

        $subscriptions = StripeManager::listSubscriptions([
            'customer' => $user->bedard_saas_customer_id,
        ]);

        return [
            'has_more'  => $subscriptions->has_more,
            'data'      => $subscriptions->data,
        ];
    }

    /**
     * Change the plan associated with a subscription.
     *
     * @param string $subscription
     */
    public function update($subscription)
    {
        $user = Auth::getUser();

        $plan = post('plan');

        $subscription = StripeManager::changeSubscriptionPlan($subscription, $plan);

        return [
            'data' => $subscription,
        ];
    }
}
