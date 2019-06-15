<?php

use Faker\Generator as Faker;

$factory->define(App\Expertise::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
            'booked Day', 'booked Rehearsal', 'booked Recovery', 'Hens Party',
            'Bucks Party', 'Engagement Party', 'Bridal Shower', 'Kitchen Tea',
            'Other Event',
        ]),
    ];
});
