<?php

use Faker\Generator as Faker;

$factory->define(App\Couple::class, function (Faker $faker) {
    return [
        'userA_id' => function () {
            return factory(App\User::class)->states('couple')->make()->id;
        },
        'title' => $faker->firstNameMale.' & '.$faker->firstNameFemale,
    ];
});
