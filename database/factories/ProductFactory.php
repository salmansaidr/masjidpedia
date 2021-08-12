<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'stock' => random_int(700,1000),
        'user_id' => User::where('role', 'supplier')->get()->random()->id
    ];
});
