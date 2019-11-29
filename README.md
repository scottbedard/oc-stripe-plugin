# oc-saas-plugin

[![Build status](https://img.shields.io/circleci/build/github/scottbedard/oc-saas-plugin)](https://circleci.com/gh/scottbedard/oc-saas-plugin)
[![Test coverage](https://img.shields.io/codecov/c/github/scottbedard/oc-saas-plugin)](https://codecov.io/gh/scottbedard/oc-saas-plugin)
[![Code quality](https://img.shields.io/scrutinizer/quality/g/scottbedard/oc-saas-plugin/master)](https://scrutinizer-ci.com/g/scottbedard/oc-saas-plugin)
[![Code style](https://github.styleci.io/repos/221099316/shield?style=flat)](https://github.styleci.io/repos/221099316)
[![License](https://img.shields.io/github/license/scottbedard/oc-saas-plugin?color=blue)](https://github.com/scottbedard/oc-saas-plugin/blob/master/LICENSE)

Software as a service plugin for October CMS and Stripe.

> **WARNING:** This plugin is in active development, and is not ready for public use. API changes may happen at any time. [See this issue](https://github.com/scottbedard/oc-saas-plugin/issues/2) for current project status.

## Installation

Install [RainLab.User](https://github.com/rainlab/user-plugin) and run all migrations, then run the following from the root October directory.

```bash
# clone repository
$ git clone git@github.com:scottbedard/oc-saas-plugin.git plugins/bedard/saas

# run migrations
$ php artisan plugin:refresh Bedard.Saas
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

Unfortunately there are no components for this plugin yet. It is currently being designed for use with [single page applications](https://github.com/scottbedard/oc-saas-theme). Hopefully once the core functionality is built, we can create a similar starter theme that uses traditional October components. If you'd like to help with this, feel free to open an issue and lets chat!

## Endpoints

An HTTP API is enabled by default. If you'd like to disable this, add the following to your `.env` file. Alternatively, you can use October's [file based configuration](https://octobercms.com/docs/plugin/settings#file-configuration).

```
BEDARD_SAAS_API_ENABLE=false
```

### Cards

##### `GET: /api/bedard/saas/user/cards`

Fetch the authenticated user's cards.

_Response_
- `data` - A [list of card objects](https://stripe.com/docs/api/cards/list?lang=php).
- `default_source` - The customer's default payment source.
- `has_more` - A boolean determing if the user has more cards not present in `data`.

##### `POST: /api/bedard/saas/user/cards`

Create a card for the authenticated user.

_Response_

- `data` - The newly created [card object](https://stripe.com/docs/api/cards/object?lang=php).

##### `DELETE: /api/bedard/saas/user/cards/{card}`

Delete a card for the authenticated user. It is recommended that you re-fetch the user's default payment source after deleting cards. See the [Stripe documentation](https://stripe.com/docs/api/cards/delete?lang=php) for more information about how default payment sources are managed when a card is deleted.

_Parameters_

- `card` - Identifier for the card to delete.

_Response_

- `deleted` - A boolean determining if the card was successfully deleted.
- `id` - Identifier for the deleted card.

### Customers

##### `POST: /api/bedard/saas/user/customer/default-source`

Update the authenticated user's default payment source.

_Payload_

- `source` - Identifier for the new default payment source.

## License

[MIT](https://github.com/scottbedard/oc-saas-plugin/blob/master/LICENSE)

Copyright (c) 2019-present, Scott Bedard
