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
        $user = Auth::getUser();

        return StripeManager::createCard($user, post('token'));
    }

    /**
     * Delete a card.
     *
     * @param string $card
     */
    public function destroy($card)
    {
        $user = Auth::getUser();

        return StripeManager::deleteCustomerSource($user, $card);
    }

    /**
     * List a user's cards.
     */
    public function index()
    {
        $user = Auth::getUser();

        $params = array_merge(input(), ['object' => 'card']);

        $customer = StripeManager::retrieveCustomer($user);
        $cards = StripeManager::listCustomerSources($user, $params);

        return [
            'data' => $cards->data,
            'default_source' => $customer->default_source,
            'has_more' => $cards->has_more,
        ];
    }
}
