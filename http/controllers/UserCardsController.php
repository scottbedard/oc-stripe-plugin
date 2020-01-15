<?php

namespace Bedard\Stripe\Http\Controllers;

use Auth;
use Bedard\Stripe\Classes\ApiController;
use StripeManager;

class UserCardsController extends ApiController
{
    /**
     * Create a card.
     */
    public function create()
    {
        $user = Auth::getUser();

        return [
            'data' => StripeManager::createCard($user, post('token')),
        ];
    }

    /**
     * Delete a card.
     *
     * @param string $card
     */
    public function destroy($card)
    {
        $user = Auth::getUser();
        $result = StripeManager::deleteCustomerSource($user, $card);

        return [
            'deleted' => $result->deleted,
            'id'      => $result->id,
        ];
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
            'data'           => $cards->data,
            'default_source' => $customer->default_source,
            'has_more'       => $cards->has_more,
        ];
    }
}
