<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function requestProduct()
    {
        return $this->belongsToMany(ProductRequest::class, 'productreq_detail', 'product_id', 'product_request_id')->withPivot('amount');
    }

    public function store()
    {
        return $this->belongsToMany(User::class, 'store_product', 'product_id', 'user_id')->withPivot('stock');
    }
}
