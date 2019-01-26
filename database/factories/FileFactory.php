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

$factory->define(App\Models\File::class, function (Faker $faker) {
    return [
        'sender_id' => $faker->numberBetween($min = 1, $max = 50),		    // Value received by the factory
        'recipient_id' => $faker->numberBetween($min = 1, $max = 50),		// Value received by the factory
        'ciphered_at' =>$faker->dateTime('now', null),
        'deciphered_at' => $faker->dateTimeBetween('2019-01-01', '2019-05-31', null),
        'hash' => $faker->unique()->sha256,			                         // Random string 
        'hash_ciphered' => $faker->unique()->sha256,	                     // Random string 
        'public_key' => $faker->unique()->sha256,		                     // Random string 
        'private_key' => $faker->unique()->sha256,		                     // Random string 
        'price' => $faker->boolean ? 0 : $faker->randomNumber(5),		     // Random string 
    ];
});