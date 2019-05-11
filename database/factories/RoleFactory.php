<?php

use Faker\Generator as Faker;

$factory->define(Dluwang\Auth\Tests\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'slug' => $faker->unique()->slug,
        'description' => $faker->text
    ];
});
