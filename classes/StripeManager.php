<?php

namespace Bedard\Stripe\Classes;

use October\Rain\Auth\AuthException;
use RainLab\User\Models\User;
use Stripe\Charge;
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
     * Cancel a user's subscription at the end of the billing period.
     *
     * @param \RainLab\User\Models\User $user
     * @param string                    $subscriptionId
     *
     * @return \Stripe\Subscription
     */
    public function cancelUserSubscription(User $user, $subscriptionId)
    {
        $subscription = Subscription::retrieve($subscriptionId);

        if ($user->bedard_stripe_customer_id !== $subscription->customer) {
            throw new AuthException('bedard.stripe::lang.exceptions.unauthorized_cancellation');
        }

        return Subscription::update($subscriptionId, [
            'cancel_at_period_end' => true,
        ]);
    }

    /**
     * Change the plan associated with a subscription.
     *
     * @param \RainLab\User\Models\User $user
     * @param string                    $subscriptionId
     * @param string                    $planId
     *
     * @return \Stripe\Subscription
     */
    public function changeUserSubscription($user, $subscriptionId, $planId)
    {
        $subscription = Subscription::retrieve($subscriptionId);

        if ($user->bedard_stripe_customer_id !== $subscription->customer) {
            throw new AuthException('bedard.stripe::lang.exceptions.unauthorized_cancellation');
        }

        return Subscription::update($subscriptionId, [
            'cancel_at_period_end' => false,
            'items'                => [
                [
                    'id'   => $subscription->items->data[0]->id,
                    'plan' => $planId,
                ],
            ],
        ]);
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
        return Customer::createSource($user->bedard_stripe_customer_id, [
            'source' => $token,
        ]);
    }

    /**
     * Create a charge.
     *
     * @param array $params
     *
     * @return \Stripe\Charge
     */
    public function createCharge(array $params = [])
    {
        return Charge::create($params);
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

        $user->bedard_stripe_customer_id = $customer->id;
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
        return Customer::deleteSource($user->bedard_stripe_customer_id, $source);
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
     * List charges.
     *
     * @param array $params
     *
     * @return array
     */
    public function listCharges(array $params)
    {
        return Charge::all($params);
    }

    /**
     * Fetch plans.
     *
     * @param array $params
     *
     * @return array
     */
    public function listPlans(array $params)
    {
        return Plan::all($params);
    }

    /**
     * Fetch products, optionally with active plans.
     *
     * @param array $params
     * @param bool  $plans
     *
     * @return array
     */
    public function listProducts(array $params, $plans = false)
    {
        $products = Product::all($params);

        if ($plans) {
            foreach ($products as $product) {
                $plans = Plan::all([
                    'active'  => true,
                    'limit'   => 100,
                    'product' => $product->id,
                ]);

                $product->plans = [
                    'data'     => $plans->data,
                    'has_more' => $plans->has_more,
                ];
            }
        }

        return $products;
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
        return Customer::allSources($user->bedard_stripe_customer_id, $params);
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
        return Customer::retrieve($user->bedard_stripe_customer_id);
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
            'customer' => $user->bedard_stripe_customer_id,
            'items'    => [
                ['plan' => $planId],
            ],
        ]));
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
        return Customer::update($user->bedard_stripe_customer_id, $data);
    }
}
