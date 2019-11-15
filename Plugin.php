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
     * Returns information about this plugin.
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
     * Registers back-end navigation items for this plugin.
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
                'url'         => Backend::url('bedard/saas/plans'),
                'sideMenu'    => [
                    'plans' => [
                        'icon'        => 'icon-cubes',
                        'label'       => 'bedard.saas::lang.navigation.plans',
                        'permissions' => ['bedard.saas.access_plans'],
                        'url'         => Backend::url('bedard/saas/plans'),
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
        ];
    }
}
