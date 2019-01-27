<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Block::class, function (Faker $faker) {
    return [
        'type' =>$faker->randomElement(['team', 'about', 'howItWorks']),
    ];
});
