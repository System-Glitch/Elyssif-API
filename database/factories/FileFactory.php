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
        // 'created_at' => now(),  - Laravel handle this by default
        // 'updated_at' => now(),  - Laravel handle this by default
        'ciphered_at' => now(),
        'deciphered_at' => now(),
        'hash' => $faker->unique()->sha256,			      // Random string 
        'hash_ciphered' => $faker->unique()->sha256,	  // Random string 
        'public_key' => str_random(16),		              // Random string 
        'private_key' => str_random(16),		          // Random string 
        'price' => $faker->randomNumber(5),			      // Random string 
    ];
});