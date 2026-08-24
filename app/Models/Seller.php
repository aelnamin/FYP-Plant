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
            \App\Models\Review::class,
            \App\Models\Product::class,
            'seller_id',
            'product_id',
            'id',
            'id'
        );
    }

    /**
     * Sellers featured in the storefront's Top Sellers section.
     */
    public function scopeStorefrontTopSellers($query)
    {
        return $query
            ->select([
                'sellers.id',
                'sellers.business_name',
                'sellers.user_id',
            ])
            ->addSelect([
                'profile_picture_path' => User::query()
                    ->select('profile_picture')
                    ->whereColumn('users.id', 'sellers.user_id')
                    ->limit(1),
            ])
            ->withAvg('reviews', 'rating')
            ->where('verification_status', 'Approved')
            ->whereNotIn('business_name', ['Green Thumb', 'Tropical Leaf'])
            ->orderByRaw('CASE WHEN business_name = ? THEN 0 ELSE 1 END', ['Orchid Hub'])
            ->orderByDesc('reviews_avg_rating');
    }

}
