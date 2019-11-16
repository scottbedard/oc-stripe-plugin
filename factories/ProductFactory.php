<?php

use Bedard\Saas\Models\Product;
use Faker\Generator;
use Illuminate\Support\Str;

/*
 * @var $factory Illuminate\Database\Eloquent\Factory
 */
$factory->define(Product::class, function (Generator $faker) {
    $name = $faker->jobTitle;

    return [
        'description' => $faker->paragraph(3),
        'is_active'   => false,
        'name'        => $name,
        'slug'        => Str::slug($name),
    ];
});
