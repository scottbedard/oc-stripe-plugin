<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use Illuminate\Support\Arr;
use StripeManager;

class CardsController extends ApiController
{
    /**
     * Create a card.
     */
    public function create()
    {
        $user = Auth::getUser();

        return StripeManager::createCard($user, post('token'));
    }

    /**
     * List a user's cards.
     */
    public function index()
    {
        $user = Auth::getUser();

        $params = array_merge(input(), ['object' => 'card']);

        $cards = StripeManager::listCustomerSources($user, $params);

        return Arr::except($cards, ['object', 'url']);
    }
}
