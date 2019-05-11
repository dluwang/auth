<?php

use Faker\Generator as Faker;

$factory->define(Dluwang\Auth\Tests\Permission::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
    ];
});
