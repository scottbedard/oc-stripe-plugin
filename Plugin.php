<?php

namespace Bedard\Stripe;

use App;
use Exception;
use Illuminate\Foundation\AliasLoader;
use Log;
use RainLab\User\Models\User;
use Stripe\Stripe;
use StripeManager;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * Stripe Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Plugin dependencies.
     */
    public $require = [
        'RainLab.User',
    ];

    /**
     * Boot method, called right before the request is routed.
     *
     * @return void
     */
    public function boot()
    {
        // register our main stripe integration
        $alias = AliasLoader::getInstance();

        $alias->alias('StripeManager', 'Bedard\Stripe\Facades\StripeManager');

        App::singleton('bedard.stripe.stripe', function () {
            return \Bedard\Stripe\Classes\StripeManager::instance();
        });

        // extend rainlab.user
        $this->extendRainLabUser();
    }

    /**
     * Extend RainLab.User plugin.
     *
     * @return void
     */
    protected function extendRainLabUser()
    {
        User::extend(function ($model) {
            $model->bindEvent('model.beforeCreate', function () use ($model) {
                StripeManager::createCustomer($model);
            });

            $model->bindEvent('model.beforeUpdate', function () use ($model) {
                StripeManager::syncCustomer($model);
            });

            $model->bindEvent('model.afterDelete', function () use ($model) {
                try {
                    StripeManager::deleteCustomer($model);
                } catch (Exception $e) {
                    Log::error($e);
                }
            });
        });
    }

    /**
     * Plugin details.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'author'      => 'Scott Bedard',
            'description' => 'Software as a service with Stripe',
            'icon'        => 'icon-cc-stripe',
            'name'        => 'Stripe',
        ];
    }

    /**
     * Registers any back-end permissions.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'bedard.stripe.access_settings' => [
                'label' => 'bedard.stripe::lang.permissions.access_settings',
                'tab'   => 'rainlab.user::lang.plugin.tab',
            ],
        ];
    }

    /**
     * Registers settings models.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'category'    => SettingsManager::CATEGORY_USERS,
                'class'       => 'Bedard\Stripe\Models\Settings',
                'description' => 'bedard.stripe::lang.settings.menu_description',
                'icon'        => 'icon-cc-stripe',
                'label'       => 'bedard.stripe::lang.settings.menu_label',
                'order'       => 600,
                'permissions' => ['bedard.stripe.access_settings'],
            ],
        ];
    }
}
