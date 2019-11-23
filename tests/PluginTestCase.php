<?php

namespace Bedard\Saas\Tests;

use App;
use Auth;
use Bedard\Saas\Models\Settings as SaasSettings;
use Config;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Notification;
use Mail;
use Mockery;
use PluginTestCase as BasePluginTestCase;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Models\User;
use System\Classes\PluginManager;

abstract class PluginTestCase extends BasePluginTestCase
{
    /**
     * @var array Plugins to refresh between tests.
     */
    protected $refreshPlugins = [
        'Bedard.Saas',
        'RainLab.User',
    ];

    /**
     * Helper function to create and re-fetch a user. The fresh user instance
     * is necessary to prevent validation errors caused by stale password fields.
     *
     * @return \RainLab\User\Models\User
     */
    public function createUser($data = [])
    {
        return User::find(factory(User::class)->create($data)->id);
    }

    /**
     * Parse a json fixture.
     *
     * @return stdClass
     */
    public static function jsonFixture(string $file)
    {
        return json_decode(file_get_contents(plugins_path('bedard/saas/tests/fixtures/'.$file)));
    }

    /**
     * Perform test case set up.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // boot all plugins
        $pluginManager = PluginManager::instance();
        $pluginManager->registerAll(true);
        $pluginManager->bootAll(true);

        // set rainlab.user min password length
        Config::set('rainlab.user::minPasswordLength', 8);

        // set stripe keys
        Config::set('services.stripe.key', env('STRIPE_KEY'));
        Config::set('services.stripe.secret', env('STRIPE_SECRET'));

        // reset any modified settings
        SaasSettings::resetDefault();
        UserSettings::resetDefault();
        UserSettings::set('activate_mode', 'auto');
        UserSettings::set('allow_registration', true);

        // register model factories
        App::singleton(Factory::class, function ($app) {
            $faker = $app->make(Generator::class);

            return Factory::construct($faker, plugins_path('bedard/saas/factories'));
        });

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

        $pluginManager = PluginManager::instance();
        $pluginManager->unregisterAll();

        Mockery::close();
    }
}
