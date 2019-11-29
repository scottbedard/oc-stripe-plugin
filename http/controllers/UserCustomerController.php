<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use StripeManager;

class UserCustomerController extends ApiController
{
    /**
     * Update the authenticated user's default payment source.
     */
    public function defaultSource()
    {
        $user = Auth::getUser();
        $source = post('source');

        StripeManager::updateCustomer($user, ['default_source' => $source]);

        return response('Ok', 200);
    }
}
