<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Skill::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->randomElement(['Takes care of Animals', 'Organizational skills', 'Taken care of kids', 'Communication skills', 'Super-coding skill']),
        'description' => $faker->sentence,
    ];
});
