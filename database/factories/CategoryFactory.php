<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->randomElement(['Education', 'Children', 'Nature', 'Health', 'Science']),
        'description' => $faker->sentence,
        'link' => '/campaigns'
    ];
});
