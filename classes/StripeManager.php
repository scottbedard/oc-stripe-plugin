<?php

namespace Bedard\Saas\Classes;

use RainLab\User\Models\User;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeManager
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
     * @param  string
     *
     * @return \Stripe\Card
     */
    public function createCard(User $user, string $token)
    {
        return Customer::createSource($user->bedard_saas_customer_id, [
            'source' => $token,
        ]);
    }

    /**
     * Create a customer from a user model.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function createCustomer(User &$user): void
    {
        $customer = Customer::create($this->getCustomerData($user));

        $user->bedard_saas_customer_id = $customer->id;
    }

    /**
     * Create a plan.
     *
     * @param array $data
     *
     * @return \Stripe\Plan
     */
    public function createPlan(array $params = [])
    {
        return Plan::create($params);
    }

    /**
     * Create a product.
     *
     * @param array $data
     *
     * @return \Stripe\Product
     */
    public function createProduct(array $params = [])
    {
        return Product::create($params);
    }

    /**
     * Subscribe a user to a plan.
     *
     * @param \RainLab\User\Models\User $user
     * @param string                    $planId
     * @param array                     $data
     *
     * @return \Stripe\Subscription
     */
    public function subscribeUserToPlan(User $user, string $planId, array $params = [])
    {
        return Subscription::create(array_merge($params, [
            'customer' => $user->bedard_saas_customer_id,
            'items'    => [
                ['plan' => $planId],
            ],
        ]));
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
     * Delete a customer's payment source.
     *
     * @param \RainLab\User\Models\User $user
     * @param string                    $source
     *
     * @return \Stripe\Customer
     */
    public function deleteCustomerSource(User $user, $source)
    {
        return Customer::deleteSource($user->bedard_saas_customer_id, $source);
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
     * Fetch products.
     *
     * @param array $params
     *
     * @return array
     */
    public function listProducts(array $params)
    {
        return Product::all($params);
    }

    /**
     * List a user's payment sources.
     *
     * @param \RainLab\User\Models\User $user
     * @param array                     $params
     *
     * @return array
     */
    public function listCustomerSources(User $user, $params = [])
    {
        return Customer::allSources($user->bedard_saas_customer_id, $params);
    }

    /**
     * List subscriptions.
     *
     * @param array $params
     *
     * @return array
     */
    public function listSubscriptions($params = [])
    {
        return Subscription::all($params);
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
     * Synchronize a user's customer data.
     *
     * @param  \RainLab\User\Models\User
     *
     * @return \Stripe\Customer
     */
    public function syncCustomer(User $user): void
    {
        $fields = ['email', 'name', 'surname'];

        if ($user->isDirty($fields)) {
            $data = $this->getCustomerData($user);

            $this->updateCustomer($user, $data);
        }
    }

    /**
     * Update a customer.
     *
     * @param \RainLab\User\Models\User $user
     * @param array                     $data
     *
     * @return \Stripe\Customer
     */
    public function updateCustomer(User $user, array $data = [])
    {
        return Customer::update($user->bedard_saas_customer_id, $data);
    }
}
