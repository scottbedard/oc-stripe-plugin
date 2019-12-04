<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
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
            'customer' => $user->bedard_saas_customer_id,
            'limit' => (int) input('limit', 10),
        ];

        $after = input('after');

        if ($after) {
            $params['starting_after'] = $after;
        }

        $charges = StripeManager::listCharges($params);

        return $charges;
    }
}