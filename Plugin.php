<?php

namespace Bedard\Saas;

use App;
use Exception;
use Illuminate\Foundation\AliasLoader;
use Log;
use RainLab\User\Models\User;
use StripeManager;
use Stripe\Stripe;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * Saas Plugin Information File.
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

        $alias->alias('StripeManager', 'Bedard\Saas\Facades\StripeManager');

        App::singleton('bedard.saas.stripe', function () {
            return \Bedard\Saas\Classes\StripeManager::instance();
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
            'name'        => 'Saas',
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
            'bedard.saas.access_settings' => [
                'label' => 'bedard.saas::lang.permissions.access_settings',
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
                'class'       => 'Bedard\Saas\Models\Settings',
                'description' => 'bedard.saas::lang.settings.menu_description',
                'icon'        => 'icon-cc-stripe',
                'label'       => 'bedard.saas::lang.settings.menu_label',
                'order'       => 600,
                'permissions' => ['bedard.saas.access_settings'],
            ],
        ];
    }
}
