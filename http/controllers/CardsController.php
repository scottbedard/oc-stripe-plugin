<?php

namespace Bedard\Saas\Http\Controllers;

use Auth;
use Bedard\Saas\Classes\ApiController;
use Illuminate\Support\Arr;
use StripeManager;

class CardsController extends ApiController
{
    protected $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->user = Auth::getUser();
    }

    /**
     * Create a card.
     */
    public function create()
    {
        return StripeManager::createCard($this->user, post('token'));
    }

    /**
     * List a user's cards.
     */
    public function index()
    {
        $params = array_merge(input(), ['object' => 'card']);

        $cards = StripeManager::listCustomerSources($this->user, $params);

        return Arr::except($cards, ['object', 'url']);
    }
}
