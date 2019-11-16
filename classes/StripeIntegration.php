<?php

namespace Bedard\Saas\Classes;

use RainLab\User\Models\User;
use Stripe\Customer;
use Stripe\Stripe;

class StripeIntegration
{
    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var StripeIntegration   Singleton instance
     */
    protected static $instance;

    /**
     * Construct.
     * 
     * @return void
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a customer from a user model.
     * 
     * @return void
     */
    public function createCustomer(User $user): void
    {
        $customer = Customer::create([
            'email' => $user->email,
        ]);

        $user->bedard_saas_customer_id = $customer->id;

        $user->forceSave();
    }

    /**
     * Retrieve a customer from a user model.
     * 
     * @return \Stripe\Customer
     */
    public function retrieveCustomer(User $user): Customer
    {
        return Customer::retrieve($user->bedard_saas_customer_id);
    }
}
