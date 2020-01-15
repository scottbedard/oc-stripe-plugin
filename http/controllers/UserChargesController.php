<?php

namespace Bedard\Stripe\Http\Controllers;

use Auth;
use Bedard\Stripe\Classes\ApiController;
use StripeManager;

class UserChargesController extends ApiController
{
    /**
     * Fetch the authenticated user's charges.
     */
    public function index()
    {
        $user = Auth::getUser();

        $params = [
            'customer' => $user->bedard_stripe_customer_id,
            'limit'    => (int) input('limit', 10),
        ];

        $after = input('after', false);
        $before = input('before', false);

        if ($after) {
            $params['starting_after'] = $after;
        } elseif ($before) {
            $params['ending_before'] = $before;
        }

        $charges = StripeManager::listCharges($params);
        $first = array_first($charges->data);
        $last = last($charges->data);
        $next = false;
        $prev = false;

        if ($before) {
            $prev = $charges->has_more;
        } elseif ($first) {
            $prev = count(StripeManager::listCharges([
                'customer'      => $user->bedard_stripe_customer_id,
                'ending_before' => $first->id,
                'limit'         => 1,
            ])->data) > 0;
        }

        if ($after) {
            $next = $charges->has_more;
        } elseif ($last) {
            $next = count(StripeManager::listCharges([
                'customer'       => $user->bedard_stripe_customer_id,
                'limit'          => 1,
                'starting_after' => $last->id,
            ])->data) > 0;
        }

        return [
            'data'     => $charges->data,
            'has_next' => $next,
            'has_prev' => $prev,
        ];
    }
}
