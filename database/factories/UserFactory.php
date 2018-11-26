<?php

use Carbon\Carbon;
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

    	'fullname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => '0127'.rand(10,99).rand(10,99).rand(10,99).'7',
        'image' => 'default.png',
        'username' => str_random(10),
        'password' => bcrypt('123456'),
        'bio' => 'Anything about me',
        'status' => 1,
        'code' => '123456',
        'firebase' => '',
        'last_seen' => Carbon::now(),
        'remember_token' => str_random(10),
    ];
});
