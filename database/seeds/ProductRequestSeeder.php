<?php

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
    }
}
