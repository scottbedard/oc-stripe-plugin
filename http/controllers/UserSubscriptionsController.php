<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use StripeManager;

class UserSubscriptionsController extends ApiController
{
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
}
