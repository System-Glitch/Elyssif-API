<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
    $paid = $faker->boolean;
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
        'address' => $paid ? $faker->unique()->sha256 : null,
        'price' => $paid ? $faker->randomFloat(8, minPrice(), 2) : 0,
    ];
});