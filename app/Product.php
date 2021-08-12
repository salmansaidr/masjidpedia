<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRequest()
    {
        return $this->hasMany(ProductRequest::class, 'product_id', 'id');
    }
}
