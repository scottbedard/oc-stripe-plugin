<?php

namespace Bedard\Saas\Models\Settings;

use Bedard\Saas\Models\Settings;
use Bedard\Saas\Tests\PluginTestCase;
use Config;

class SettingsTest extends PluginTestCase
{
    public function test_api_config_missing()
    {
        Config::set('services.stripe.key', '');
        Config::set('services.stripe.secret', '');

        $this->assertEquals(0, Settings::apiKeysConfigured());
    }

    public function test_api_config_mixed_environment()
    {
        Config::set('services.stripe.key', 'pk_test_XXXXXXXXXXXXXXXXXXXXXX');
        Config::set('services.stripe.secret', 'sk_live_XXXXXXXXXXXXXXXXXXXXXX');

        $this->assertEquals(0, Settings::apiKeysConfigured());
    }

    public function test_api_config_mismatched_keys()
    {
        Config::set('services.stripe.key', 'sk_test_XXXXXXXXXXXXXXXXXXXXXX');
        Config::set('services.stripe.secret', 'pk_test_XXXXXXXXXXXXXXXXXXXXXX');

        $this->assertEquals(0, Settings::apiKeysConfigured());
    }

    public function test_api_config_testing()
    {
        Config::set('services.stripe.key', 'pk_test_XXXXXXXXXXXXXXXXXXXXXX');
        Config::set('services.stripe.secret', 'sk_test_XXXXXXXXXXXXXXXXXXXXXX');

        $this->assertEquals(1, Settings::apiKeysConfigured());
    }

    public function test_api_config_live()
    {
        Config::set('services.stripe.key', 'pk_live_XXXXXXXXXXXXXXXXXXXXXX');
        Config::set('services.stripe.secret', 'sk_live_XXXXXXXXXXXXXXXXXXXXXX');

        $this->assertEquals(2, Settings::apiKeysConfigured());
    }

    public function test_currency_code_defaults_to_usd()
    {
        $this->assertEquals('USD', Settings::get('currency_code'));
    }
}
