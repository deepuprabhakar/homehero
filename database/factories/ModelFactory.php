<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

/*$factory->define(App\Location::class, function (Faker\Generator $faker) {
    return [
        'type' => ucfirst( str_random(6) ),
        'sub_type' => ucfirst( str_random(6) ),
        'loc_id' => 'Loc' . str_pad(1, 3, '0', STR_PAD_LEFT),
    ];
});*/
