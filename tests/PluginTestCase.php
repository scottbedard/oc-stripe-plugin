<?php

namespace Bedard\Saas\Tests;

use App;
use Auth;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Notification;
use Mail;
use Mockery;
use PluginTestCase as BasePluginTestCase;
use RainLab\User\Models\Settings as UserSettings;
use System\Classes\PluginManager;

abstract class PluginTestCase extends BasePluginTestCase
{
    /**
     * @var array Plugins to refresh between tests.
     */
    protected $refreshPlugins = [
        'RainLab.User',
    ];

    /**
     * Perform test case set up.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // boot all plugins
        PluginManager::instance()->bootAll(true);

        // reset any modified settings
        UserSettings::resetDefault();
        UserSettings::set('activate_mode', 'auto');
        UserSettings::set('allow_registration', true);

        // register the Auth facade in our test environment
        $alias = AliasLoader::getInstance();
        $alias->alias('Auth', 'RainLab\User\Facades\Auth');

        App::singleton('user.auth', function () {
            return \RainLab\User\Classes\AuthManager::instance();
        });

        // Log the user out
        Auth::logout();

        // october's "pretend" method doesn't quite reach the level
        // of testability we're after. so we'll use the built in
        // "fake" method instead. the only downside to doing this
        // is that we cannot use the "quick sending" methods.
        Mail::fake();

        // disable notifications during tests
        Notification::fake();
    }

    /**
     * Perform tear down.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}
