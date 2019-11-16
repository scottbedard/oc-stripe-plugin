<?php

namespace Bedard\Saas\Classes;

use RainLab\User\Models\User;
use Stripe\Customer;
use Stripe\Stripe;

class StripeIntegration
{
    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var StripeIntegration Singleton instance
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
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function createCustomer(User $user): Customer
    {
        $customer = Customer::create($this->getCustomerData($user));

        $user->bedard_saas_customer_id = $customer->id;
        $user->forceSave();

        return $customer;
    }

    /**
     * Permenantly delete a customer. This cannot be undone, and immediately
     * cancels any active subscriptions on the customer.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function deleteCustomer(User $user): Customer
    {
        $customer = $this->retrieveCustomer($user);

        return $customer->delete();
    }

    /**
     * Returns data to sync between User models and Stripe Customer objects.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return array
     */
    protected function getCustomerData(User $user): array
    {
        return [
            'email' => $user->email,
            'name'  => $user->name.' '.$user->surname,
        ];
    }

    /**
     * Retrieve a customer from a user model.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function retrieveCustomer(User $user): Customer
    {
        return Customer::retrieve($user->bedard_saas_customer_id);
    }

    /**
     * Update a customer from a user model.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function updateCustomer(User $user): Customer
    {
        return Customer::update($user->bedard_saas_customer_id, $this->getCustomerData($user));
    }
}
