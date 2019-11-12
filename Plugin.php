<?php

namespace Bedard\Saas;

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
            'author'        => 'Scott Bedard',
            'description'   => 'Software as a service with Stripe',
            'icon'          => 'icon-cc-stripe',
            'name'          => 'Saas',
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
        return [];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [];
    }
}
