<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Achievement::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
    ];
});
