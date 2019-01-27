<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Step::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'campaign_id' => $faker->numberBetween(1, 50),
        'active' => $faker->randomElement([1,0]),
    ];
});
