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

$factory->define(App\Models\Contact::class, function (Faker $faker) {
    return [
        'id_user' => $faker->numberBetween($min = 1, $max = 50),			// Doit ciblé une ID existente
        'id_contacts' => $faker->numberBetween($min = 1, $max = 50),		// Doit ciblé une ID existente
        'notes' => $faker->text($maxNbChars = 200),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});