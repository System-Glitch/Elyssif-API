<?php

use Faker\Generator as Faker;
use App\Models\User;

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


$factory->define(App\Models\File::class, function (Faker $faker) {
    $userCount = User::all()->count();
    return [
        'sender_id' => $faker->numberBetween(1, $userCount),
        'recipient_id' => $faker->numberBetween(1, $userCount),
        'ciphered_at' =>$faker->dateTime('now', null),
        'deciphered_at' => $faker->dateTimeBetween('2019-01-01', '2019-05-31', null),
        'name' => $faker->word,
        'hash' => $faker->unique()->sha256,
        'hash_ciphered' => $faker->unique()->sha256,
        'public_key' => $faker->unique()->sha256,
        'private_key' => $faker->unique()->sha256,
        'elyssif_addr' => $faker->unique()->sha256,
        'price' => $faker->boolean ? 0 : $faker->randomFloat(8, 0.00001, 2),
    ];
});