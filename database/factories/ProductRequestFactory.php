<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\ProductRequest;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProductRequest::class, function (Faker $faker) {
    $product_id = Product::all()->random()->id;
    $user = User::where('role', 'toko')->get()->random()->id;
    return [
        'amount' => random_int(50, 100),
        'user_id' => $user,
        'product_id' => $product_id
    ];
});
