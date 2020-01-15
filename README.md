# oc-stripe-plugin

[![Build status](https://img.shields.io/circleci/build/github/scottbedard/oc-stripe-plugin)](https://circleci.com/gh/scottbedard/oc-stripe-plugin)
[![Test coverage](https://img.shields.io/codecov/c/github/scottbedard/oc-stripe-plugin)](https://codecov.io/gh/scottbedard/oc-stripe-plugin)
[![Code quality](https://img.shields.io/scrutinizer/quality/g/scottbedard/oc-stripe-plugin/master)](https://scrutinizer-ci.com/g/scottbedard/oc-stripe-plugin)
[![Code style](https://github.styleci.io/repos/221099316/shield?style=flat)](https://github.styleci.io/repos/221099316)
[![License](https://img.shields.io/github/license/scottbedard/oc-stripe-plugin?color=blue)](https://github.com/scottbedard/oc-stripe-plugin/blob/master/LICENSE)

Software as a service plugin for October CMS and Stripe.

> **WARNING:** This plugin is in active development, and is not ready for public use. API changes may happen at any time. [See this issue](https://github.com/scottbedard/oc-stripe-plugin/issues/2) for current project status.

## Installation

Install [RainLab.User](https://github.com/rainlab/user-plugin) and run all migrations, then run the following from the root October directory.

```bash
# clone repository
$ git clone git@github.com:scottbedard/oc-stripe-plugin.git plugins/bedard/stripe

# run migrations
$ php artisan plugin:refresh Bedard.Stripe
```

Once this is done, configure your publishable and secret Stripe keys. Do this by adding the following to `config/services.php`.

```php
'stripe' => [
    'key'     => env('STRIPE_KEY'),
    'secret'  => env('STRIPE_SECRET'),
],
```

Finally, add the following to your `.env` file.

```
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXXXXXXXX
```

## Components

Unfortunately there are no components for this plugin yet. It is currently being designed for use with [single page applications](https://github.com/scottbedard/oc-stripe-theme). Hopefully once the core functionality is built, we can create a similar starter theme that uses traditional October components. If you'd like to help with this, feel free to open an issue and lets chat!

## Endpoints

An HTTP API is enabled by default. If you'd like to disable this, add the following to your `.env` file. Alternatively, you can use October's [file based configuration](https://octobercms.com/docs/plugin/settings#file-configuration).

```
BEDARD_STRIPE_API_ENABLE=false
```

### Cards

##### `GET: /api/bedard/stripe/user/cards`

Fetch the authenticated user's cards.

_Response_
- `data` - A [list of card objects](https://stripe.com/docs/api/cards/list?lang=php).
- `default_source` - The customer's default payment source.
- `has_more` - A boolean indicating if the user has more cards.

##### `POST: /api/bedard/stripe/user/cards`

Create a card for the authenticated user.

_Response_

- `data` - The newly created [card object](https://stripe.com/docs/api/cards/object?lang=php).

##### `DELETE: /api/bedard/stripe/user/cards/{card}`

Delete a card for the authenticated user. It is recommended that you re-fetch the user's default payment source after deleting cards. See the [Stripe documentation](https://stripe.com/docs/api/cards/delete?lang=php) for more information about how default payment sources are managed when a card is deleted.

_Parameters_

- `card` - Identifier for the card to delete.

_Response_

- `deleted` - A boolean indicating if the card was successfully deleted.
- `id` - Identifier for the deleted card.

### Charges

##### `GET: /api/bedard/stripe/user/charges`

List charges by the authenticated user.

_Parameters_

- `after` - Selects charges after a given ID.
- `before` - Selects charges before a given ID.
- `limit` - Number of results to fetch, defaults to 10.

_Response_

- `data` - A [list of charge objects](https://stripe.com/docs/api/charges/list?lang=php).
- `has_next` - Indicates there are older charges after the last returned result.
- `has_prev` - Indicates there are newer charges before the first returned result.

### Customers

##### `POST: /api/bedard/stripe/user/customer/default-source`

Update the authenticated user's default payment source.

_Payload_

- `source` - Identifier for the new default payment source.

### Products

##### `GET: /api/bedard/stripe/products`

Fetch active products.

_Parameters_

- `plans` - Includes active plans with the products. Be aware, this will result in multiple requests to Stripe being made to fetch the related plans. If you have many active products, it is probably better to fetch active plans and [expand the product objects](https://stripe.com/docs/api/expanding_objects?lang=php).

_Response_

- `data` - A [list of product objects](https://stripe.com/docs/api/service_products/list?lang=php).
- `has_more` - A boolean indicating if there are more products.

### Subscriptions

##### `GET: /api/bedard/stripe/user/subscriptions`

List the authenticated user's subscriptions.

_Response_

- `data` - A [list of subscription objects](https://stripe.com/docs/api/subscriptions/list?lang=php).
- `has_more` - A boolean indicating if there are more subscriptions.

##### `POST: /api/bedard/stripe/user/subscriptions`

Create a subscription for the authenticated user. The user must have at least one card on file before creating a subscription.

_Payload_

- `plan` - Plan identifier to subscribe the user to.

_Response_

- `data` - The newly created [subscription object](https://stripe.com/docs/api/subscriptions/object?lang=php).

##### `DELETE: /api/bedard/stripe/user/subscriptions/{subscription}`

Lazily cancels a user's subscription. Note that this is not the same as calling a subscription's `cancel` method, and does not cancel the user's subscription immediately. Instead, calling this endpoint sets the [`cancel_at_period_end`](https://stripe.com/docs/api/subscriptions/object?lang=php#subscription_object-cancel_at_period_end) property to `true`, and the user will continue to have access until the end of their billing cycle.

_Parameters_

- `subscription` - Subscription ID to cancel.

##### `PATCH/PUT: /api/bedard/stripe/user/subscriptions/{subscription}`

Change the plan associated with a user's subscription. If the subscription has `cancel_at_period_end` set to `true`, this will be updated to `false` and resume normal billing.

_Parameters_

- `subscription` - Subscription ID to change.

_Payload_

- `plan` - Plan ID to set subscription to.

_Response_

- `data` - The [subscription object](https://stripe.com/docs/api/subscriptions/update?lang=php) that has been updated.

## License

[MIT](https://github.com/scottbedard/oc-stripe-plugin/blob/master/LICENSE)

Copyright (c) 2019-present, Scott Bedard
