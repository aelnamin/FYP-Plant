<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_address',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    // Seller.php
    public function reviews()
    {
        return $this->hasManyThrough(
            \App\Models\Review::class, // Target model
            \App\Models\Product::class, // Intermediate model
            'seller_id',               // Foreign key on Product (products.seller_id)
            'product_id',              // Foreign key on Review (reviews.product_id)
            'id',                      // Local key on Seller (sellers.id)
            'id'                       // Local key on Product (products.id)
        );
    }

}
