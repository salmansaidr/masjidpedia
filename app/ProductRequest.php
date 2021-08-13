<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $table = 'product_requests';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'productreq_detail', 'product_request_id', 'product_id')->withPivot('amount');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }
}
