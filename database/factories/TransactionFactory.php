<?php

use Faker\Generator as Faker;
use App\Models\Transaction;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'txid' => $faker->unique()->sha256,
        'confirmed' => $faker->boolean,
        'amount' => $faker->randomFloat(8, 0.000001, 1)
    ];
});
