<?php

namespace Bedard\Saas\Updates;

use App;
use Bedard\Saas\Models\Product;
use Bedard\Saas\Models\Schedule;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use October\Rain\Database\Updates\Seeder;

class seed_tables extends Seeder
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
        // do nothing during unit tests
        if (app()->env === 'testing') {
            return;
        }

        $this->seedProducts();
    }

    protected function seedProducts()
    {
        $standard = factory(Product::class)->create([
            'is_active' => true,
            'name'      => 'Standard',
            'slug'      => 'standard',
        ]);

        factory(Schedule::class)->states('monthly')->create([
            'cost'       => 10,
            'product_id' => $standard->id,
        ]);

        factory(Schedule::class)->states('yearly')->create([
            'cost'       => 100,
            'product_id' => $standard->id,
        ]);

        $premium = factory(Product::class)->create([
            'is_active' => true,
            'name'      => 'Premium',
            'slug'      => 'premium',
        ]);

        factory(Schedule::class)->states('monthly')->create([
            'cost'       => 20,
            'product_id' => $premium->id,
        ]);

        factory(Schedule::class)->states('quarterly')->create([
            'cost'       => 55,
            'product_id' => $premium->id,
        ]);

        factory(Schedule::class)->states('yearly')->create([
            'cost'       => 200,
            'product_id' => $premium->id,
        ]);

        $agency = factory(Product::class)->create([
            'name' => 'Agency',
            'slug' => 'agency',
        ]);
    }
}
