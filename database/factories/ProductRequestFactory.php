<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\ProductRequest;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProductRequest::class, function (Faker $faker) {
    $product_id = Product::all()->random()->id;
    $user = User::where('role', 'toko')->get()->random()->id;
    $supplier = User::where('role', 'supplier')->get()->random()->id;
    return [
        'supplier_id' => $supplier,
        'user_id' => $user,
    ];
});
