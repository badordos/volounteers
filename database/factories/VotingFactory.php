<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Voting::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'variants' => serialize([
            ['title'=> $faker->word],
            ['title'=> $faker->word],
            ['title'=> $faker->word],
            ['title'=> $faker->word]
        ]),
        'step_id' => $faker->unique()->numberBetween(1, 250),
    ];
});
