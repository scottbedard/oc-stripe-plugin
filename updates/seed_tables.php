<?php

namespace Bedard\Saas\Updates;

use App;
use Bedard\Saas\Models\Plan;
use Bedard\Saas\Models\Schedule;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use October\Rain\Database\Updates\Seeder;

class SeedAllTables extends Seeder
{
    public function __construct()
    {
        // register model factories
        App::singleton(Factory::class, function ($app) {
            $faker = $app->make(Generator::class);

            return Factory::construct($faker, plugins_path('bedard/saas/factories'));
        });
    }

    public function run()
    {
        $this->seedPlans();
    }

    protected function seedPlans()
    {
        $standard = factory(Plan::class)->create([
            'is_active' => true,
            'name' => 'Standard',
            'slug' => 'standard',
        ]);

        factory(Schedule::class)->states('monthly')->create([
            'cost' => 10,
            'plan_id' => $standard->id,
        ]);

        factory(Schedule::class)->states('yearly')->create([
            'cost' => 100,
            'plan_id' => $standard->id,
        ]);

        $premium = factory(Plan::class)->create([
            'is_active' => true,
            'name' => 'Premium',
            'slug' => 'premium',
        ]);

        factory(Schedule::class)->states('monthly')->create([
            'cost' => 20,
            'plan_id' => $premium->id,
        ]);

        factory(Schedule::class)->states('quarterly')->create([
            'cost' => 55,
            'plan_id' => $premium->id,
        ]);

        factory(Schedule::class)->states('yearly')->create([
            'cost' => 200,
            'plan_id' => $premium->id,
        ]);

        $agency = factory(Plan::class)->create([
            'name' => 'Agency',
            'slug' => 'agency',
        ]);
    }
}
