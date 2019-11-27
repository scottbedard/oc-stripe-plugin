<?php

namespace Bedard\Saas\Classes;

use RainLab\User\Models\User;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Product;
use Stripe\Stripe;

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
     * List active products with associated plans.
     *
     * @param array $params
     *
     * @return array
     */
    public function getProductsWithPlans()
    {
        $products = Product::all([
            'active' => true,
        ]);

        foreach ($products->data as $product) {
            $plans = Plan::all([
                'active'  => true,
                'product' => $product->id,
            ]);

            $product->plans = StripeUtils::sort($plans->data, ['metadata.order', 'amount']);
        }

        $data = StripeUtils::sort($products->data, ['metadata.order']);

        return $data;
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
    public function updateCustomer(User $user): void
    {
        $fields = ['email', 'name', 'surname'];

        if ($user->isDirty($fields)) {
            $id = $user->bedard_saas_customer_id;
            $data = $this->getCustomerData($user);

            Customer::update($id, $data);
        }
    }
}
