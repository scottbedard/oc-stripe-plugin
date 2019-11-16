<?php

namespace Bedard\Saas;

use App;
use Backend;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User;
use StripeIntegration;
use System\Classes\PluginBase;

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
        // register our stripe integration singleton
        $alias = AliasLoader::getInstance();
        $alias->alias('StripeIntegration', 'Bedard\Saas\Facades\StripeIntegration');

        App::singleton('bedard.saas.stripe', function () {
            return \Bedard\Saas\Classes\StripeIntegration::instance();
        });

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
            $model->bindEvent('model.afterCreate', function () use ($model) {
                StripeIntegration::createCustomer($model);
            });

            $model->bindEvent('model.afterUpdate', function () use ($model) {
                StripeIntegration::updateCustomer($model);
            });

            $model->bindEvent('model.afterDelete', function () use ($model) {
                StripeIntegration::deleteCustomer($model);
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
     * Registers back-end navigation items.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'saas' => [
                'icon'        => 'icon-credit-card',
                'label'       => 'bedard.saas::lang.navigation.label',
                'order'       => 500,
                'permissions' => ['bedard.saas.*'],
                'url'         => Backend::url('bedard/saas/products'),
                'sideMenu'    => [
                    'products' => [
                        'icon'        => 'icon-cubes',
                        'label'       => 'bedard.saas::lang.navigation.products',
                        'permissions' => ['bedard.saas.access_products'],
                        'url'         => Backend::url('bedard/saas/products'),
                    ],
                    'settings' => [
                        'icon'        => 'icon-cog',
                        'label'       => 'bedard.saas::lang.navigation.settings',
                        'permissions' => ['bedard.saas.access_settings'],
                        'url'         => Backend::url('system/settings/update/bedard/saas/settings'),
                    ],
                ],
            ],
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
            'bedard.saas.access_products' => [
                'label' => 'bedard.saas::lang.permissions.access_products',
                'tab'   => 'bedard.saas::lang.permissions.tab',
            ],
            'bedard.saas.access_settings' => [
                'label' => 'bedard.saas::lang.permissions.access_settings',
                'tab'   => 'bedard.saas::lang.permissions.tab',
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
                'category'    => 'bedard.saas::lang.settings.menu_category',
                'class'       => 'Bedard\Saas\Models\Settings',
                'description' => 'bedard.saas::lang.settings.menu_description',
                'icon'        => 'icon-cc-stripe',
                'label'       => 'bedard.saas::lang.settings.menu_label',
                'order'       => 500,
                'permissions' => ['bedard.saas.access_settings'],
            ],
        ];
    }
}
