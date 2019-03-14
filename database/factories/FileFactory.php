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
    $nbUsers = User::all()->count();
    return [
        'sender_id' => $faker->numberBetween($min = 1, $max = $nbUsers),		    // Value received and overwritten by the factory
        'recipient_id' => $faker->numberBetween($min = 1, $max = $nbUsers),		// Value received and overwritten by the factory
        'ciphered_at' =>$faker->dateTime('now', null),
        'deciphered_at' => $faker->dateTimeBetween('2019-01-01', '2019-05-31', null),
        'name' => $faker->word,
        'hash' => $faker->unique()->sha256,			                         // Random string 
        'hash_ciphered' => $faker->unique()->sha256,	                     // Random string 
        'public_key' => $faker->unique()->sha256,		                     // Random string 
        'private_key' => $faker->unique()->sha256,		                     // Random string 
        'price' => $faker->boolean ? 0 : $faker->randomNumber(5),		     // Random string 
    ];
});