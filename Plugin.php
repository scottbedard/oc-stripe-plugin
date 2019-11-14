<?php

namespace Bedard\Saas;

use Backend;
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
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'author' => 'Scott Bedard',
            'description' => 'Software as a service with Stripe',
            'icon' => 'icon-cc-stripe',
            'name' => 'Saas',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'saas' => [
                'icon' => 'icon-credit-card',
                'label' => 'bedard.saas::lang.navigation.label',
                'order' => 500,
                'permissions' => ['bedard.saas.*'],
                'url' => Backend::url('bedard/saas/plans'),
                'sideMenu' => [
                    'plans' => [
                        'icon' => 'icon-cubes',
                        'label' => 'bedard.saas::lang.navigation.plans',
                        'permissions' => ['bedard.saas.access_plans'],
                        'url' => Backend::url('bedard/saas/plans'),
                    ],
                    'schedules' => [
                        'icon' => 'icon-calendar',
                        'label' => 'bedard.saas::lang.navigation.schedules',
                        'permissions' => ['bedard.saas.access_schedules'],
                        'url' => Backend::url('bedard/saas/schedules'),
                    ],
                ],
            ],
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'bedard.saas.access_plans' => [
                'label' => 'bedard.saas::lang.permissions.access_plans',
                'tab'   => 'bedard.saas::lang.permissions.tab',
            ],
            'bedard.saas.access_schedules' => [
                'label' => 'bedard.saas::lang.permissions.access_schedules',
                'tab' => 'bedard.saas::lang.permissions.tab',
            ]
        ];
    }
}
