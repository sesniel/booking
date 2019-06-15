<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'fname' => $faker->firstname,
        'lname' => $faker->lastname,
        'password' => str_random(10),
        'remember_token' => str_random(10),
        'avatar' => 'https://placeimg.com/300/300/any',
        'status' => 'active',
        'account' => $faker->randomElement(['couple', 'vendor']),
    ];
});

$factory->state(App\User::class, 'admin', [
    'account' => 'admin',
]);

$factory->state(App\User::class, 'couple', [
    'account' => 'couple',
]);

$factory->state(App\User::class, 'vendor', [
    'account' => 'vendor',
]);

$factory->state(App\User::class, 'unknown', [
    'account' => null,
]);
