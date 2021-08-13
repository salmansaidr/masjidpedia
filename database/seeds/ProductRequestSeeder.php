<?php

use App\Product;
use App\ProductRequest;
use Illuminate\Database\Seeder;

class ProductRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ProductRequest::class, 30)->create();

        ProductRequest::all()->each(function($proReq) {
            $products = Product::where('user_id', $proReq->supplier_id)->get();

            $proReq->product()->attach(
                $products->random()->pluck('id')->toArray()
            , [
                'amount' => random_int(50, 100)
            ]);
        });
    }
}
