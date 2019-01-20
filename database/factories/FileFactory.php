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
        'sender_id' => $faker->numberBetween($min = 1, $max = 50),		// Doit ciblé une ID existente
        'recipient_id' => $faker->numberBetween($min = 1, $max = 50),		// Doit ciblé une ID existente
        'created_at' => now(),
        'updated_at' => now(),
        'ciphered_at' => now(),
        'deciphered_at' => now(),
        'hash' => $faker->unique()->sha256,			      // Chaine de caractère aléatoire 
        'hash_ciphered' => $faker->unique()->sha256,	  // Chaine de caractère aléatoire 
        'public_key' => str_random(16),		                          // Chaine de caractère aléatoire 
        'private_key' => str_random(16),		                          // Chaine de caractère aléatoire 
        'price' => $faker->randomNumber(5),			                          // Chaine de caractère aléatoire 
    ];
});