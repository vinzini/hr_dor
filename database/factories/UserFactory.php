<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Roles;
use Illuminate\Support\Str;
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

$factory->define(Roles::class, function (Faker $faker) {
    return [
        'role' => null,
    ];
});

$factory->define(User::class, function (Faker $faker) {
	$roles = Roles::all()->random();
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(123456),
        'role_id' => $roles->id,
        'company_id' => null,
        'country_code' => $faker->countryCode,
    ];
});
