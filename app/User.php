<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

 
    public function products()
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class, 'user_id', 'id');
    }

    public function requestAsSupplier() {
        return $this->hasMany(ProductRequest::class, 'supplier_id', 'id');
    }

    public function store()
    {
        return $this->belongsToMany(Product::class, 'store_product', 'user_id', 'product_id',)->withPivot('stock');
    }
}
