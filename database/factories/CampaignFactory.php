<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Campaign::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'volunteers_needed' => rand(10, 1000),
        'about_desc' => $faker->text,
        'city_id' => rand(1,10),
        'category_id' => rand(1,5),
        'user_id' => rand(1,10),
        'readiness' => 'success',
    ];
});
