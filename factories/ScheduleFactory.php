<?php

use Bedard\Saas\Models\Schedule;
use Faker\Generator;

/*
 * @var $factory Illuminate\Database\Eloquent\Factory
 */
$factory->define(Schedule::class, function (Generator $faker) {
    return [
        'cost' => 10,
    ];
});

$factory->state(Schedule::class, 'biweekly', [
    'calendar_duration' => 14,
    'calendar_unit'     => 'day',
    'name'              => 'Bi-Weekly',
]);

$factory->state(Schedule::class, 'monthly', [
    'calendar_duration' => 1,
    'calendar_unit'     => 'month',
    'name'              => 'Monthly',
]);

$factory->state(Schedule::class, 'quarterly', [
    'calendar_duration' => 3,
    'calendar_unit'     => 'month',
    'name'              => 'Quarterly',
]);

$factory->state(Schedule::class, 'yearly', [
    'calendar_duration' => 1,
    'calendar_unit'     => 'year',
    'name'              => 'Annual',
]);
