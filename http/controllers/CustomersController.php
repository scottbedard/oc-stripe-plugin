<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use StripeManager;

class CustomersController extends ApiController
{
    /**
     * Update the authenticated user's customer object.
     */
    public function update()
    {
        $user = Auth::getUser();
        $data = post();

        return StripeManager::updateCustomer($user, $data);
    }
}
