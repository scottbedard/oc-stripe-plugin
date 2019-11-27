<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use StripeManager;

class CardsController extends ApiController
{
    /**
     * Create a card.
     */
    public function create()
    {
        $token = post('token');
        $user = Auth::getUser();

        return StripeManager::createCard($user, $token);
    }
}
