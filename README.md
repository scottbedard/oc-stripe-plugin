# oc-saas-plugin

[![Build status](https://img.shields.io/travis/scottbedard/oc-saas-plugin)](https://travis-ci.org/scottbedard/oc-saas-plugin)
[![Test coverage](https://img.shields.io/codecov/c/github/scottbedard/oc-saas-plugin)](https://codecov.io/gh/scottbedard/oc-saas-plugin)
[![Code quality](https://img.shields.io/scrutinizer/quality/g/scottbedard/oc-saas-plugin/master)](https://scrutinizer-ci.com/g/scottbedard/oc-saas-plugin)
[![License](https://img.shields.io/github/license/scottbedard/oc-saas-plugin?color=blue)](https://github.com/scottbedard/oc-saas-plugin/blob/master/LICENSE)

Software as a service plugin for October CMS and Stripe.

> **Note:** This plugin is in active development, and is not ready for public use. [See this issue](https://github.com/scottbedard/oc-saas-plugin/issues/2) for current project status.

### Installation

Install [RainLab.User](https://github.com/rainlab/user-plugin) and run all migrations, then run the following from the root October directory.

```bash
# clone repository
git clone git@github.com:scottbedard/oc-saas-plugin.git plugins/bedard/saas

# run migrations
php artisan plugin:refresh Bedard.Saas
```

### License

[MIT](https://github.com/scottbedard/oc-saas-plugin/blob/master/LICENSE)

Copyright (c) 2019-present, Scott Bedard
