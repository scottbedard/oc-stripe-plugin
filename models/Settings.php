<?php

namespace Bedard\Saas\Models;

use Illuminate\Support\Str;
use Model;

/**
 * Settings Model.
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Behaviors implemented by this model.
     */
    public $implement = [
        \System\Behaviors\SettingsModel::class,
    ];

    public $settingsCode = 'bedard_saas_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * Determine if Stripe API keys are configured.
     *
     * 0 = api keys are missing or invalid
     * 1 = api is configured with test keys
     * 2 = api is configured with live keys
     *
     * @return int
     */
    public static function apiKeysConfigured()
    {
        $key = config('services.stripe.key');
        $secret = config('services.stripe.secret');

        if (
            Str::startsWith($key, 'pk_live_') &&
            Str::startsWith($secret, 'sk_live_')
        ) {
            return 2;
        }

        if (
            Str::startsWith($key, 'pk_test_') &&
            Str::startsWith($secret, 'sk_test_')
        ) {
            return 1;
        }

        return 0;
    }

    /**
     * Get the currency code.
     *
     * @return string
     */
    public function getCurrencyCodeAttribute()
    {
        return self::get('currency_code', 'USD');
    }
}
